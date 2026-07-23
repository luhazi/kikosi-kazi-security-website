<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\CandidateEducation;
use App\Models\CandidateExperience;
use App\Models\Document;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Candidate\ProfileController;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class CvParserController extends Controller
{
    /**
     * Parse an uploaded CV document and return structured JSON.
     * Accepts a document_id (must belong to the authenticated candidate, file_type = cv).
     */
    public function parse(Request $request): JsonResponse
    {
        try {
            $request->validate(['document_id' => ['required', 'integer']]);

            $profile = Auth::user()->candidateProfile;

            if (! $profile) {
                return response()->json(['error' => 'Candidate profile not found. Please complete your profile setup first.'], 422);
            }

            $document = Document::where('id', $request->document_id)
                                ->where('candidate_id', $profile->id)
                                ->where('file_type', 'cv')
                                ->first();

            if (! $document) {
                return response()->json(['error' => 'CV document not found.'], 404);
            }

            $fullPath = Storage::disk('public')->path($document->file_path);

            if (! file_exists($fullPath)) {
                return response()->json(['error' => 'CV file not found on the server.'], 404);
            }

            $mime = strtolower($document->mime_type ?? '');
            $ext  = strtolower(pathinfo($document->file_path, PATHINFO_EXTENSION));
            $text = '';

            $isPdf  = $mime === 'application/pdf' || $ext === 'pdf';
            $isWord = str_contains($mime, 'wordprocessingml') || $mime === 'application/msword'
                      || in_array($ext, ['docx', 'doc']);

            if ($isPdf) {
                $text = $this->extractPdf($fullPath);
            } elseif ($isWord) {
                $text = $this->extractDocx($fullPath);
            } else {
                return response()->json([
                    'error' => 'Only PDF and Word (.docx) files can be parsed. Please upload your CV in one of these formats.',
                ], 422);
            }

            if (empty(trim($text))) {
                return response()->json([
                    'error' => 'Could not extract readable text from this CV. ' .
                               'It may be a scanned/image-based PDF. Please upload a Word (.docx) version instead.',
                ], 422);
            }

            return response()->json([
                'success' => true,
                'data'    => $this->parseText($text),
            ], 200, [], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Parse error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Save imported CV data to the candidate's profile, education, and experience.
     */
    public function import(Request $request): RedirectResponse
    {
        $profile = Auth::user()->candidateProfile;

        // ── Personal profile fields ──────────────────────────────────────
        $fill = [];
        foreach (['phone', 'city', 'region', 'nationality', 'address', 'bio'] as $field) {
            $val = trim((string) $request->input($field, ''));
            if ($val !== '') $fill[$field] = $val;
        }
        if (! empty($fill)) {
            $profile->update($fill);
        }

        // ── Education ────────────────────────────────────────────────────
        foreach ((array) $request->input('edu', []) as $edu) {
            $inst  = trim((string) ($edu['institution']   ?? ''));
            $qual  = trim((string) ($edu['qualification'] ?? ''));
            $field = trim((string) ($edu['field']         ?? ''));
            $year  = (int) ($edu['year'] ?? 0);
            $grade = trim((string) ($edu['grade'] ?? ''));

            if (empty($inst) || empty($qual) || empty($field) || $year < 1950 || $year > 2099) {
                continue;
            }

            CandidateEducation::create([
                'candidate_id'   => $profile->id,
                'institution'    => $inst,
                'qualification'  => $qual,
                'field_of_study' => $field,
                'year_completed' => $year,
                'grade'          => $grade ?: null,
            ]);
        }

        // ── Work experience ──────────────────────────────────────────────
        foreach ((array) $request->input('exp', []) as $exp) {
            $employer  = trim((string) ($exp['employer']         ?? ''));
            $title     = trim((string) ($exp['job_title']        ?? ''));
            $startRaw  = trim((string) ($exp['start_date']       ?? ''));
            $endRaw    = trim((string) ($exp['end_date']         ?? ''));
            $desc      = trim((string) ($exp['responsibilities'] ?? ''));
            $isCurrent = ! empty($exp['is_current']);

            if (empty($employer) || empty($title)) continue;

            $startDate = $this->toDate($startRaw);
            if (! $startDate) continue;

            $endDate = $isCurrent ? null : $this->toDate($endRaw);

            CandidateExperience::create([
                'candidate_id'     => $profile->id,
                'employer'         => $employer,
                'job_title'        => $title,
                'start_date'       => $startDate,
                'end_date'         => $endDate,
                'responsibilities' => $desc ?: 'N/A',
            ]);
        }

        // Recalculate completeness after import
        $profile->completeness_pct = ProfileController::calculateCompleteness($profile);
        $profile->save();

        return redirect()->route('candidate.profile.index')
                         ->with('success', 'CV data imported to your profile. Please review and save any changes.');
    }

    // =========================================================================
    // Text extraction
    // =========================================================================

    private function extractPdf(string $path): string
    {
        // 1. smalot/pdfparser — pure PHP, works on any OS, handles most text-based PDFs
        if (class_exists(\Smalot\PdfParser\Parser::class)) {
            try {
                $parser = new \Smalot\PdfParser\Parser();
                $pdf    = $parser->parseFile($path);
                $text   = $pdf->getText();
                if (! empty(trim($text))) {
                    return $this->cleanText($text);
                }
            } catch (\Throwable $e) {
                // Fall through to next method
            }
        }

        // 2. System pdftotext (Linux/macOS with Poppler installed)
        if (function_exists('shell_exec')) {
            foreach (['pdftotext', '/usr/bin/pdftotext', '/usr/local/bin/pdftotext'] as $bin) {
                $out = (string) @shell_exec($bin . ' ' . escapeshellarg($path) . ' - 2>/dev/null');
                if (! empty(trim($out))) {
                    return $out;
                }
            }
        }

        // 3. Raw PDF stream extraction (last resort)
        return $this->extractPdfRaw($path);
    }

    private function extractPdfRaw(string $path): string
    {
        $raw = (string) @file_get_contents($path);
        if ($raw === '') return '';

        // Attempt to decode zlib-compressed content streams
        $decoded = '';
        if (preg_match_all('/stream\r?\n(.*?)\r?\nendstream/s', $raw, $sm)) {
            foreach ($sm[1] as $s) {
                $h = substr($s, 0, 2);
                if ($h === "\x78\x9C" || $h === "\x78\x01" || $h === "\x78\xDA") {
                    $d = @gzuncompress($s);
                    if ($d !== false) $decoded .= $d . "\n";
                }
            }
        }

        $src  = $decoded ?: $raw;
        $text = '';

        // Extract text from BT...ET (PDF text operators)
        if (preg_match_all('/BT(.*?)ET/s', $src, $bt)) {
            foreach ($bt[1] as $block) {
                if (preg_match_all('/\(([^)]*)\)\s*Tj/s', $block, $tj)) {
                    foreach ($tj[1] as $c) $text .= $c . ' ';
                }
                if (preg_match_all('/\[([^\]]*)\]\s*TJ/s', $block, $TJ)) {
                    foreach ($TJ[1] as $c) {
                        if (preg_match_all('/\(([^)]*)\)/', $c, $inner)) {
                            $text .= implode('', $inner[1]) . ' ';
                        }
                    }
                }
                // Treat position commands as newlines
                if (preg_match('/T[dm]\b/', $block)) $text .= "\n";
            }
        }

        return $this->cleanText($text ?: $src);
    }

    private function extractDocx(string $path): string
    {
        $zip = new ZipArchive();
        if ($zip->open($path) !== true) return '';

        $xml = $zip->getFromName('word/document.xml');
        $zip->close();

        if (! $xml) return '';

        // Paragraph and line-break elements -> real newlines
        $xml = preg_replace('/<w:p[ >]/', "\n<w:p>", (string) $xml) ?? $xml;
        $xml = preg_replace('/<w:br[^\/]*\/>/', "\n", $xml) ?? $xml;
        $xml = preg_replace('/<w:tab[^\/]*\/>/', " ", $xml) ?? $xml;

        return $this->cleanText(
            html_entity_decode(strip_tags($xml), ENT_QUOTES | ENT_HTML5, 'UTF-8')
        );
    }

    private function cleanText(string $text): string
    {
        // Ensure valid UTF-8 — strip or recode invalid byte sequences
        if (function_exists('iconv')) {
            $safe = @iconv('UTF-8', 'UTF-8//IGNORE', $text);
            if ($safe === false) {
                $enc  = mb_detect_encoding($text, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true);
                $safe = mb_convert_encoding($text, 'UTF-8', $enc ?: 'ISO-8859-1');
            }
            $text = $safe;
        } elseif (! mb_check_encoding($text, 'UTF-8')) {
            $enc  = mb_detect_encoding($text, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true);
            $text = mb_convert_encoding($text, 'UTF-8', $enc ?: 'ISO-8859-1');
        }

        $text = preg_replace('/[ \t]+/', ' ', $text)    ?? $text;
        $text = preg_replace('/\n{3,}/', "\n\n", $text) ?? $text;
        // Strip non-printable control characters
        $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $text) ?? $text;
        // Strip bullet / list symbols that PDF parsers emit as Unicode
        $text = preg_replace('/[\x{2022}\x{2023}\x{2043}\x{25A0}-\x{25CF}\x{2219}\x{2630}\x{2261}\x{00B7}\x{25E6}\x{21B3}]/u', ' ', $text) ?? $text;
        return trim($text);
    }

    // =========================================================================
    // Text parsing
    // =========================================================================

    private function parseText(string $text): array
    {
        return [
            'phone'       => $this->extractPhone($text),
            'email'       => $this->extractEmail($text),
            'city'        => $this->extractCity($text),
            'region'      => $this->extractRegion($text),
            'nationality' => $this->extractNationality($text),
            'address'     => $this->extractAddress($text),
            'bio'         => $this->extractBio($text),
            'education'   => $this->extractEducation($text),
            'experience'  => $this->extractExperience($text),
        ];
    }

    private function extractPhone(string $t): string
    {
        // Truncate at REFEREES/REFERENCES section so we never pick up referee phone numbers
        $refPos = null;
        foreach (['REFEREES', 'REFERENCES', 'Referees', 'References'] as $kw) {
            $p = strpos($t, $kw);
            if ($p !== false && ($refPos === null || $p < $refPos)) $refPos = $p;
        }
        $search = $refPos ? substr($t, 0, $refPos) : $t;

        // 1. Try header block (first 600 chars) for a bare Tanzania number — most CVs list it unlabelled at top
        $header = substr($search, 0, 600);
        if (preg_match('/\b(\+?255\s?[067][\d][\s\-]?[\d]{3}[\s\-]?[\d]{3}[\s\-]?[\d]{0,3})\b/', $header, $m)) {
            return trim($m[1]);
        }
        if (preg_match('/\b(0[67]\d{8})\b/', $header, $m)) {
            return $m[1];
        }

        // 2. Labelled phone anywhere before referees section
        if (preg_match(
            '/(?:phone|tel(?:ephone)?|mobile|cell(?:ular)?|mob|contact|simu|namba)[^\S\n]*[:\s]+(\+?[\d][\d\s\-().]{6,18}[\d])/i',
            $search, $m
        )) {
            $num = preg_replace('/[^\d+\s\-()]/', '', trim($m[1]));
            if (preg_match('/\d{7,15}/', preg_replace('/\D/', '', $num))) {
                return preg_replace('/\s+/', ' ', $num);
            }
        }

        // 3. Any Tanzania number in the truncated text
        if (preg_match('/\b(\+?255\s?[067][\d][\s\-]?[\d]{3}[\s\-]?[\d]{3}[\s\-]?[\d]{0,3})\b/', $search, $m)) {
            return trim($m[1]);
        }
        if (preg_match('/\b(0[67]\d{8})\b/', $search, $m)) {
            return $m[1];
        }
        return '';
    }

    private function extractEmail(string $t): string
    {
        if (preg_match('/[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,6}/', $t, $m)) {
            return strtolower($m[0]);
        }
        return '';
    }

    private function extractAddress(string $t): string
    {
        if (preg_match('/(?:address|location|residence|makazi)[^\S\n]*[:\s]+([^\n]{5,120})/i', $t, $m)) {
            return trim($m[1]);
        }
        return '';
    }

    private function extractCity(string $t): string
    {
        $cities = [
            'Dar es Salaam','Dodoma','Arusha','Mwanza','Zanzibar City','Zanzibar','Mbeya',
            'Morogoro','Tanga','Kigoma','Moshi','Tabora','Iringa','Mtwara','Shinyanga',
            'Sumbawanga','Songea','Lindi','Musoma','Bukoba',
        ];
        foreach ($cities as $c) {
            if (stripos($t, $c) !== false) return $c;
        }
        if (preg_match('/(?:city|town|mji)[^\S\n]*[:\s]+([A-Za-z\s]{3,30}?)(?:\n|,|\.)/i', $t, $m)) {
            return trim($m[1]);
        }
        return '';
    }

    private function extractRegion(string $t): string
    {
        $regions = [
            'Arusha','Dar es Salaam','Dodoma','Geita','Iringa','Kagera','Katavi','Kigoma',
            'Kilimanjaro','Lindi','Manyara','Mara','Mbeya','Morogoro','Mtwara','Mwanza',
            'Njombe','Pwani','Rukwa','Ruvuma','Shinyanga','Simiyu','Singida','Tabora',
            'Tanga','Zanzibar',
        ];
        if (preg_match('/(?:region|mkoa)[^\S\n]*[:\s]+([A-Za-z\s]{3,30}?)(?:\n|,|\.)/i', $t, $m)) {
            $r = trim($m[1]);
            foreach ($regions as $reg) {
                if (stripos($r, $reg) !== false) return $reg;
            }
            return $r;
        }
        foreach ($regions as $reg) {
            if (stripos($t, $reg) !== false) return $reg;
        }
        return '';
    }

    private function extractNationality(string $t): string
    {
        if (preg_match('/(?:nationality|uraia|citizenship)[^\S\n]*[:\s]+([A-Za-z\s]{3,30}?)(?:\n|,|\.)/i', $t, $m)) {
            return trim($m[1]);
        }
        $map = ['tanzanian' => 'Tanzanian', 'kenyan' => 'Kenyan', 'ugandan' => 'Ugandan', 'rwandan' => 'Rwandan'];
        foreach ($map as $kw => $val) {
            if (stripos($t, $kw) !== false) return $val;
        }
        return '';
    }

    private function extractBio(string $t): string
    {
        if (preg_match(
            '/(?:objective|summary|profile|about me|career summary|professional summary)\s*[:\-]?\s*\n?((?:.+\n?){1,8})/i',
            $t, $m
        )) {
            // Cut at the next all-caps section header
            $parts = preg_split('/\n(?=[A-Z ]{5,}[\n:])/m', $m[1]);
            return trim($parts[0] ?? '');
        }
        return '';
    }

    private function extractEducation(string $text): array
    {
        $results = [];

        if (! preg_match('/(?:education|academic|qualification|mafunzo)[s]?\s*[\n:]/i', $text, $m, PREG_OFFSET_CAPTURE)) {
            return $results;
        }

        $after = substr($text, $m[0][1] + strlen($m[0][0]));

        // All section headers that end the education block.
        // Critically: "referee" (covers REFEREES/REFEREE) as well as "reference".
        $sectionKw = 'experience|employment|work\s*history|career\s*history|professional\s+experience'
                   . '|skills?|technical\s+skills?|core\s+competenc'
                   . '|certif|professional\s+registration'
                   . '|referees?|references?'
                   . '|awards?|achievements?|honours?'
                   . '|interests?|hobbies?|activities'
                   . '|languages?'
                   . '|objective|summary|profile';

        $cutoff = preg_match(
            '/\n(?:' . $sectionKw . ')[s]?\s*[\n:]/i',
            $after, $m2, PREG_OFFSET_CAPTURE
        ) ? $m2[0][1] : min(strlen($after), 2500);

        $section = substr($after, 0, $cutoff);

        // Also cut at any ALL-CAPS standalone header line (e.g. "REFEREES", "SKILLS")
        // that the keyword pattern above may have missed
        if (preg_match('/\n([A-Z][A-Z &\/\-]{3,})\n/', $section, $capM, PREG_OFFSET_CAPTURE)) {
            $section = substr($section, 0, $capM[0][1]);
        }

        $lines = array_values(array_filter(
            array_map('trim', preg_split('/[\n\r]+/', $section)),
            fn($l) => strlen($l) > 2
        ));

        $kwDeg  = ['bachelor','master','phd','doctorate','diploma','certificate','degree',
                   'bsc','msc','mba','hnd','btech','beng','b.sc','m.sc','b.eng','m.eng',
                   'arts','science','commerce','engineering','law','medicine','nursing',
                   'accounting','business','education','information technology','computer',
                   'electronics','communication','finance','economics','management'];
        $kwInst = ['university','college','school','institute','polytechnic','academy'];

        // Lines whose text alone marks them as non-education content
        $garbageRx = '/^(?:referees?|references?|skills?|certifications?|awards?|interests?'
                   . '|hobbies?|available upon request|n\/a|see above)\s*$/i';

        $cur       = null;
        $carryQual = ''; // qualification line seen before its institution line

        foreach ($lines as $line) {
            $lo     = strtolower($line);
            $isDeg  = (bool) array_filter($kwDeg,  fn($k) => str_contains($lo, $k));
            $isInst = (bool) array_filter($kwInst, fn($k) => str_contains($lo, $k));
            $hasYr  = (bool) preg_match('/\b(19|20)\d{2}\b/', $line);

            // Skip section-header bleed-through lines
            if (preg_match($garbageRx, $line)) {
                continue;
            }

            if ($isDeg || $isInst) {
                if ($cur) {
                    // If the current entry is ONLY a floating qualification (no institution, no year)
                    // it means the CV lists degree first, university second.
                    // Don't save it yet — carry the qualification to the next institution line.
                    if (empty($cur['institution']) && ! empty($cur['qualification']) && empty($cur['year_completed'])) {
                        $carryQual = $cur['qualification'];
                        $cur = null;
                    } elseif (! empty($cur['institution']) || ! empty($cur['qualification'])) {
                        $results[] = $cur;
                        $cur = null;
                    }
                }

                $cur = ['institution' => '', 'qualification' => '', 'field_of_study' => '', 'year_completed' => '', 'grade' => ''];

                if ($isInst) {
                    $cur['institution'] = $this->cleanInstitutionName($line);
                    // Attach the carried-over qualification (degree line came before university line)
                    if ($carryQual !== '') {
                        $cur['qualification'] = $carryQual;
                        $carryQual = '';
                    }
                } else {
                    // Degree keyword line — store as qualification; clear any stale carry
                    $cur['qualification'] = $line;
                    $carryQual = '';
                }
            } elseif ($cur !== null && ! $hasYr) {
                if      (empty($cur['qualification']))   $cur['qualification']  = $line;
                elseif  (empty($cur['institution']))     $cur['institution']    = $this->cleanInstitutionName($line);
                elseif  (empty($cur['field_of_study']))  $cur['field_of_study'] = $line;
            }

            if ($cur && $hasYr && empty($cur['year_completed'])) {
                // Use LAST year in line — "2014–2018" → 2018 (graduation year)
                preg_match_all('/\b(19|20)\d{2}\b/', $line, $yr);
                $cur['year_completed'] = end($yr[0]);
            }
            if ($cur && preg_match('/(?:grade|gpa|cgpa|class|division)[:\s]+([^\n,]{1,20})/i', $line, $gr)) {
                $cur['grade'] = trim($gr[1]);
            }
        }
        if ($cur && (! empty($cur['institution']) || ! empty($cur['qualification']))) {
            $results[] = $cur;
        }

        // Infer field_of_study from qualification string if still empty
        // e.g. "BEng in Electronics" → "Electronics"
        // e.g. "Bachelor of Human Resource Management" → "Human Resource Management"
        foreach ($results as &$entry) {
            if (empty($entry['field_of_study']) && ! empty($entry['qualification'])) {
                if (preg_match('/\bin\s+(.{4,80})$/i', $entry['qualification'], $fi)) {
                    $entry['field_of_study'] = trim($fi[1]);
                } elseif (preg_match('/\bof\s+(.{4,80})$/i', $entry['qualification'], $fi)) {
                    $entry['field_of_study'] = trim($fi[1]);
                }
            }
        }
        unset($entry);

        // Final filter: drop entries whose core fields are garbage strings
        $results = array_values(array_filter($results, function ($e) use ($garbageRx) {
            if (preg_match($garbageRx, $e['institution']   ?? '')) return false;
            if (preg_match($garbageRx, $e['qualification'] ?? '')) return false;
            if (preg_match($garbageRx, $e['field_of_study'] ?? '')) return false;
            // Must have at least a recognisable qualification
            if (empty($e['institution']) && empty($e['qualification'])) return false;
            return true;
        }));

        return array_slice($results, 0, 6);
    }

    /**
     * Strip graduation meta-text that PDF parsers sometimes merge into the institution name.
     * e.g. "Mzumbe University | Graduated: 2020" → "Mzumbe University"
     *       "University of DSM – Completed 2018"  → "University of DSM"
     */
    private function cleanInstitutionName(string $name): string
    {
        // Strip " | Graduated: YYYY" and similar pipe-separated annotations
        $name = preg_replace('/\s*[|]\s*(Graduated|Grad\.?|Completed|Finished)[:\s]*\d{0,4}.*/i', '', $name) ?? $name;
        // Strip trailing " - Graduated 2020" or ", Graduated 2020"
        $name = preg_replace('/[,\s]+[-–—]?\s*(Graduated|Grad\.?|Completed|Finished)[:\s]*\d{0,4}.*/i', '', $name) ?? $name;
        // Strip bare trailing year range " (2015-2018)" or " 2015–2018"
        $name = preg_replace('/\s*[\(]?\b(19|20)\d{2}\s*[-–—\/to]+\s*(19|20|\d{2})\d{2}\b[\)]?.*$/i', '', $name) ?? $name;
        return trim($name);
    }

    private function extractExperience(string $text): array
    {
        $results = [];

        if (! preg_match('/(?:professional\s+)?(?:experience|employment|work history|career)[s]?\s*[\n:]/i', $text, $m, PREG_OFFSET_CAPTURE)) {
            return $results;
        }

        $after  = substr($text, $m[0][1] + strlen($m[0][0]));

        // Truncate at next major section — includes referees/referee (not just reference)
        $expSectionKw = 'certif|professional\s+registration'
                      . '|education|academic|qualification'
                      . '|skills?|technical\s+skills?|core\s+competenc'
                      . '|referees?|references?'
                      . '|languages?|awards?|achievements?'
                      . '|interests?|hobbies?|activities'
                      . '|objective|summary|profile';

        $cutoff = preg_match(
            '/\n(?:' . $expSectionKw . ')[s]?\s*[\n:]/i',
            $after, $m2, PREG_OFFSET_CAPTURE
        ) ? $m2[0][1] : min(strlen($after), 6000);

        $section = substr($after, 0, $cutoff);
        $lines   = array_values(array_filter(
            array_map('trim', preg_split('/[\n\r]+/', $section)),
            fn($l) => strlen($l) > 2
        ));

        // Keywords that indicate a company/employer name
        $companyKw = ['ltd','limited','company','corp','inc','plc','group','technologies',
                      'solutions','services','associates','enterprises','holdings','agency',
                      'international','consultancy','telecom','bank','authority','ministry',
                      'ngo','foundation','institute','university','college','government',
                      'construction','building','materials','tanzania','kenya','uganda'];

        $cur = null;
        foreach ($lines as $line) {
            // Match date ranges: "Feb 2022 – Present", "2020 – 2022", "Ongoing"
            $hasRange = (bool) preg_match(
                '/\b((?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\w*\s+)?(19|20)\d{2}\b'
                . '.{0,20}[-\x{2013}\x{2014}\/to\s]+'
                . '\s*(\b((?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\w*\s+)?'
                . '(19|20)\d{2}\b|present|current|ongoing|till date|to date)/iu',
                $line
            );

            if ($hasRange) {
                if ($cur && (! empty($cur['employer']) || ! empty($cur['job_title']))) {
                    $cur['responsibilities'] = $this->cleanResponsibilities(trim($cur['responsibilities']));
                    $results[] = $cur;
                }
                $cur = ['employer' => '', 'job_title' => '', 'start_date' => '', 'end_date' => '', 'responsibilities' => '', 'is_current' => false];

                // Start date
                if (preg_match('/\b((Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\w*\s+)?((?:19|20)\d{2})\b/i', $line, $d1)) {
                    $cur['start_date'] = trim((! empty($d1[1]) ? trim($d1[1]) . ' ' : '') . $d1[3]);
                }
                // End date or open-ended
                if (preg_match('/(?:present|current|ongoing|till date|to date)/i', $line)) {
                    $cur['is_current'] = true;
                } elseif (preg_match('/[-\x{2013}\x{2014}\/to]+\s*((Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\w*\s+)?((?:19|20)\d{2})\b/iu', $line, $d2)) {
                    $cur['end_date'] = trim((! empty($d2[1]) ? trim($d2[1]) . ' ' : '') . $d2[3]);
                }

                // Job title on same line as date range (text before the first month/year)
                $beforeDate = trim(preg_replace('/\b(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\w*\s+(19|20)\d{2}.*/i', '', $line));
                $beforeDate = trim(preg_replace('/\b(19|20)\d{2}.*/i', '', $beforeDate));
                $beforeDate = trim(preg_replace('/[-\x{2013}\x{2014}]+\s*$/u', '', $beforeDate));
                if (strlen($beforeDate) > 2 && ! preg_match('/^[,.\-–—\s]+$/', $beforeDate)) {
                    $lo = strtolower($beforeDate);
                    $isCompany = (bool) array_filter($companyKw, fn($k) => str_contains($lo, $k));
                    if ($isCompany) $cur['employer']  = $beforeDate;
                    else            $cur['job_title'] = $beforeDate;
                }

            } elseif ($cur !== null) {
                $lo        = strtolower($line);
                $isCompany = (bool) array_filter($companyKw, fn($k) => str_contains($lo, $k));
                // Skip very long lines that are clearly bulk responsibility text (>120 chars with no date)
                $isBulk = strlen($line) > 120 && ! preg_match('/\b(19|20)\d{2}\b/', $line);

                if ($isCompany && empty($cur['employer'])) {
                    $cur['employer'] = $line;
                } elseif (empty($cur['job_title']) && ! $isBulk) {
                    $cur['job_title'] = $line;
                } elseif (empty($cur['employer']) && ! $isBulk) {
                    $cur['employer'] = $line;
                } else {
                    $cur['responsibilities'] .= $line . ' ';
                }
            }
        }
        if ($cur && (! empty($cur['employer']) || ! empty($cur['job_title']))) {
            $cur['responsibilities'] = $this->cleanResponsibilities(trim($cur['responsibilities']));
            $results[] = $cur;
        }

        return array_slice($results, 0, 10);
    }

    // =========================================================================
    // Responsibility formatting
    // =========================================================================

    /**
     * Split a run-on responsibilities string into bullet lines and strip any
     * section headers (Certifications, Referees, etc.) that bled through.
     */
    private function cleanResponsibilities(string $text): string
    {
        // Remove section headers that bled through (e.g. "CERTIFICATIONS & PROFESSIONAL REGISTRATION...")
        $text = preg_replace(
            '/(?:CERTIFICATIONS?(?:\s*[&]\s*PROFESSIONAL\s+REGISTRATION)?'
            . '|REFEREES?|REFERENCES?|TECHNICAL\s+SKILLS?|SKILLS?'
            . '|EDUCATION|LANGUAGES?|AWARDS?|INTERESTS?).*/si',
            '', $text
        );

        $text = trim($text);
        if (empty($text)) return '';

        // If already has newlines / bullet markers, just clean them
        if (str_contains($text, "
") || str_contains($text, '•')) {
            $lines = preg_split('/[
]+/', $text);
            $out   = [];
            foreach ($lines as $l) {
                $l = trim($l, " 	•·▪-–—");
                if (strlen($l) > 10) $out[] = '• ' . rtrim($l, '.');
            }
            return implode("
", $out);
        }

        // Split plain-text run-on sentences on ". " followed by a capital letter
        $sentences = preg_split('/(?<=[.!?])\s+(?=[A-Z\d])/', $text);
        $out = [];
        foreach ($sentences as $s) {
            $s = trim($s, " 	
");
            if (strlen($s) > 10) {
                $out[] = '• ' . rtrim($s, '.');
            }
        }
        return implode("
", $out) ?: $text;
    }

    // =========================================================================
    // Date helpers
    // =========================================================================

    private function toDate(string $val): ?string
    {
        $val = trim($val);
        if (empty($val)) return null;

        // "YYYY-MM" from input[type=month] -> append day
        if (preg_match('/^(19|20)\d{2}-\d{2}$/', $val)) return $val . '-01';

        // Plain year
        if (preg_match('/^(19|20)\d{2}$/', $val)) return $val . '-01-01';

        // "Mon YYYY" or "Month YYYY" e.g. "Jan 2020", "January 2020"
        $months = ['jan'=>1,'feb'=>2,'mar'=>3,'apr'=>4,'may'=>5,'jun'=>6,
                   'jul'=>7,'aug'=>8,'sep'=>9,'oct'=>10,'nov'=>11,'dec'=>12];
        if (preg_match('/^([A-Za-z]{3,9})\s+((?:19|20)\d{2})$/', $val, $m)) {
            $mon = $months[strtolower(substr($m[1], 0, 3))] ?? null;
            if ($mon) return $m[2] . '-' . str_pad((string)$mon, 2, '0', STR_PAD_LEFT) . '-01';
        }

        // strtotime fallback
        $ts = strtotime($val);
        if ($ts !== false) return date('Y-m-d', $ts);

        return null;
    }
}
