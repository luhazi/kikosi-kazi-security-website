<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function index(): View
    {
        $documents = Auth::user()
            ->candidateProfile
            ->documents()
            ->orderBy('file_type')
            ->get()
            ->groupBy('file_type');

        return view('candidate.documents.index', compact('documents'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'file'      => ['required', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:5120'],
            'file_type' => ['required', 'in:cv,national_id,certificate,passport_photo,other'],
        ]);

        $profile = Auth::user()->candidateProfile;

        $path = $request->file('file')->store("documents/{$profile->id}", 'public');

        Document::create([
            'candidate_id'  => $profile->id,
            'file_type'     => $request->file_type,
            'file_path'     => $path,
            'original_name' => $request->file('file')->getClientOriginalName(),
            'file_size'     => $request->file('file')->getSize(),
            'mime_type'     => $request->file('file')->getMimeType(),
        ]);

        // Keep profile completeness in sync (an academic certificate counts toward 100%)
        $profile->completeness_pct = ProfileController::calculateCompleteness($profile->fresh());
        $profile->save();

        return redirect()->back()->with('success', 'Document uploaded successfully.');
    }

    public function preview(Document $document): mixed
    {
        if ($document->candidate_id !== Auth::user()->candidateProfile->id) {
            abort(403);
        }

        if (! Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File not found.');
        }

        $mime = $document->mime_type
            ?? Storage::disk('public')->mimeType($document->file_path);

        // Word docs → Google Docs Viewer
        if (in_array($mime, [
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])) {
            $publicUrl = url(Storage::url($document->file_path));
            return redirect('https://docs.google.com/gview?url=' . urlencode($publicUrl) . '&embedded=true');
        }

        // PDF & images → serve inline so the browser renders them
        return response()->file(
            Storage::disk('public')->path($document->file_path),
            [
                'Content-Type'        => $mime,
                'Content-Disposition' => 'inline; filename="' . ($document->original_name ?? basename($document->file_path)) . '"',
            ]
        );
    }

    public function download(Document $document): StreamedResponse
    {
        if ($document->candidate_id !== Auth::user()->candidateProfile->id) {
            abort(403);
        }

        if (! Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('public')->download(
            $document->file_path,
            $document->original_name ?? basename($document->file_path)
        );
    }

    public function destroy(Document $document): RedirectResponse
    {
        if ($document->candidate_id !== Auth::user()->candidateProfile->id) {
            abort(403);
        }

        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        // Removing a certificate may lower completeness — keep it in sync
        $profile = Auth::user()->candidateProfile;
        $profile->completeness_pct = ProfileController::calculateCompleteness($profile->fresh());
        $profile->save();

        return redirect()->back()->with('success', 'Document removed.');
    }
}
