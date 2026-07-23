<?php

namespace App\Services;

use App\Models\CandidateProfile;

/**
 * Discipline Matching Service
 *
 * Maps a candidate's field(s) of study to broad discipline categories
 * and checks whether they are eligible to apply for a given job.
 *
 * Discipline keys are stored on the jobs.discipline column.
 * NULL or 'any' means the job is open to all disciplines.
 */
class DisciplineService
{
    // ── Discipline category definitions ─────────────────────────────────────

    /**
     * All supported discipline keys → human-readable labels.
     * 'any' is always included as a special value meaning "open to all".
     */
    public static function categories(): array
    {
        return [
            'any'            => 'Open to All Disciplines',
            'engineering'    => 'Engineering',
            'it'             => 'Information Technology & Computer Science',
            'accounting'     => 'Accounting & Finance',
            'hr'             => 'Human Resources & Management',
            'medicine'       => 'Medicine, Health & Nursing',
            'law'            => 'Law & Legal Studies',
            'business'       => 'Business Administration',
            'education'      => 'Education & Teaching',
            'agriculture'    => 'Agriculture & Environmental Science',
            'social_science' => 'Social Sciences & Development Studies',
            'architecture'   => 'Architecture, Construction & Urban Planning',
            'science'        => 'Natural Sciences (Biology, Chemistry, Physics)',
            'tourism'        => 'Tourism, Hospitality & Hotel Management',
            'media'          => 'Media, Journalism & Communication',
            'procurement'    => 'Procurement, Logistics & Supply Chain',
        ];
    }

    // ── Keyword → discipline mapping ─────────────────────────────────────────

    /**
     * Maps lowercase keywords found in field_of_study / qualification
     * to a discipline key.  Order matters: more specific entries first.
     */
    private static function keywordMap(): array
    {
        return [
            'engineering'    => [
                'engineering', 'mechanical', 'electrical', 'civil', 'chemical',
                'electronic', 'structural', 'petroleum', 'mining', 'geotechnical',
                'telecommunication', 'power systems', 'industrial engineering',
                'environmental engineering',
            ],
            'it'             => [
                'computer science', 'information technology', 'ict', 'software',
                'computing', 'data science', 'cybersecurity', 'network',
                'systems analysis', 'artificial intelligence', 'machine learning',
                'information systems', 'computer engineering',
            ],
            'accounting'     => [
                'accounting', 'finance', 'cpa', 'acca', 'financial management',
                'economics', 'commerce', 'actuarial', 'taxation', 'auditing',
                'banking', 'investment',
            ],
            'hr'             => [
                'human resource', 'human resources', 'hr management',
                'personnel management', 'organizational development',
                'industrial relations', 'labour relations',
            ],
            'medicine'       => [
                'medicine', 'nursing', 'pharmacy', 'medical', 'health sciences',
                'dentistry', 'public health', 'clinical', 'midwifery',
                'physiotherapy', 'radiography', 'laboratory sciences', 'optometry',
                'mental health', 'nutrition', 'dietetics',
            ],
            'law'            => [
                'law', 'legal', 'jurisprudence', 'llb', 'llm',
                'international law', 'commercial law',
            ],
            'business'       => [
                'business administration', 'mba', 'management', 'marketing',
                'entrepreneurship', 'project management', 'strategic management',
                'operations management', 'supply management', 'business management',
            ],
            'education'      => [
                'education', 'teaching', 'pedagogy', 'curriculum', 'early childhood',
                'special needs education', 'adult education', 'educational leadership',
            ],
            'agriculture'    => [
                'agriculture', 'agronomy', 'horticulture', 'animal science',
                'food science', 'food technology', 'environmental science',
                'forestry', 'fisheries', 'wildlife', 'soil science',
                'agribusiness', 'veterinary',
            ],
            'social_science' => [
                'sociology', 'psychology', 'social work', 'development studies',
                'anthropology', 'political science', 'international relations',
                'gender studies', 'community development', 'demography',
                'criminology', 'peace studies',
            ],
            'architecture'   => [
                'architecture', 'urban planning', 'quantity surveying',
                'construction management', 'real estate', 'land management',
                'interior design', 'building economics', 'surveying',
            ],
            'science'        => [
                'biology', 'chemistry', 'physics', 'mathematics', 'statistics',
                'geology', 'natural sciences', 'biochemistry', 'microbiology',
                'zoology', 'botany', 'marine science',
            ],
            'tourism'        => [
                'tourism', 'hospitality', 'hotel management', 'travel',
                'event management', 'tour operations',
            ],
            'media'          => [
                'journalism', 'communication', 'mass communication', 'media',
                'public relations', 'broadcasting', 'film', 'photography',
                'digital media', 'advertising',
            ],
            'procurement'    => [
                'procurement', 'logistics', 'supply chain', 'materials management',
                'warehousing', 'customs', 'freight', 'transport management',
            ],
        ];
    }

    // ── Public API ───────────────────────────────────────────────────────────

    /**
     * Detect all discipline keys that match a raw field/qualification string.
     * Returns an array of matching discipline keys (may be empty if unknown).
     */
    public static function detectFromText(string $text): array
    {
        $lower   = strtolower($text);
        $matches = [];

        foreach (self::keywordMap() as $discipline => $keywords) {
            foreach ($keywords as $kw) {
                if (str_contains($lower, $kw)) {
                    $matches[] = $discipline;
                    break; // only add each discipline once
                }
            }
        }

        return array_unique($matches);
    }

    /**
     * Return all discipline keys that match the candidate's education records.
     * Checks both field_of_study and qualification columns.
     */
    public static function candidateDisciplines(CandidateProfile $profile): array
    {
        $disciplines = [];

        foreach ($profile->education as $edu) {
            $text = ($edu->field_of_study ?? '') . ' ' . ($edu->qualification ?? '');
            foreach (self::detectFromText($text) as $d) {
                $disciplines[] = $d;
            }
        }

        return array_unique($disciplines);
    }

    /**
     * Check whether a candidate is eligible to apply for a job with the
     * given discipline key.
     *
     * Rules:
     *  - job discipline is null / 'any'  → everyone is eligible
     *  - candidate has no education records → NOT eligible (unless job is 'any')
     *  - candidate's detected disciplines contain the job's discipline → eligible
     */
    public static function isEligible(CandidateProfile $profile, ?string $jobDiscipline): bool
    {
        $required = self::jobDisciplines($jobDiscipline);

        if (empty($required)) {
            return true; // open to all disciplines
        }

        $candidateDisciplines = self::candidateDisciplines($profile);

        if (empty($candidateDisciplines)) {
            return false; // no recognisable education records → can't verify match
        }

        // Eligible if the candidate matches ANY of the job's required disciplines
        return count(array_intersect($required, $candidateDisciplines)) > 0;
    }

    /**
     * Parse a stored job discipline value (a single key or a comma-separated
     * list of keys) into a clean array of valid discipline keys.
     * Empty / 'any' → empty array, meaning "open to all disciplines".
     */
    public static function jobDisciplines(?string $value): array
    {
        if (empty($value)) {
            return [];
        }

        $valid = array_keys(self::categories());
        $keys  = array_filter(array_map('trim', explode(',', $value)));
        $keys  = array_values(array_intersect($keys, $valid));

        return array_values(array_filter($keys, fn ($k) => $k !== 'any'));
    }

    /**
     * Human-readable label for a discipline value. Handles a single key or a
     * comma-separated list, and returns the labels joined with commas.
     */
    public static function label(?string $value): string
    {
        $keys = self::jobDisciplines($value);

        if (empty($keys)) {
            return 'Open to All Disciplines';
        }

        $cats = self::categories();

        return implode(', ', array_map(
            fn ($k) => $cats[$k] ?? ucfirst(str_replace('_', ' ', $k)),
            $keys
        ));
    }
}
