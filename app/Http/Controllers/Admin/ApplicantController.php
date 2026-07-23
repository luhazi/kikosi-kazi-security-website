<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Document;
use App\Models\ApplicationStatusLog;
use App\Models\CandidateProfile;
use App\Models\Job;
use App\Notifications\ApplicationStatusChanged;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ApplicantController extends Controller
{
    public function index(Request $request): View
    {
        $query = CandidateProfile::with(['user', 'applications'])->withCount('applications');

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('job_id')) {
            $query->whereHas('applications', function ($q) use ($request) {
                $q->where('job_id', $request->job_id);
            });
        }

        $candidates = $query->latest()->paginate(20)->withQueryString();
        $allJobs = Job::orderBy('title')->get();

        return view('admin.applicants.index', compact('candidates', 'allJobs'));
    }

    public function show(CandidateProfile $candidateProfile): View
    {
        $candidateProfile->load(['user', 'education', 'experience', 'documents', 'applications.job']);

        return view('admin.applicants.show', compact('candidateProfile'));
    }

    public function updateStatus(Request $request, Application $application): RedirectResponse
    {
        $validated = $request->validate([
            'status' => [
                'required',
                'in:submitted,under_review,shortlisted,interview_scheduled,successful,rejected,withdrawn',
            ],
            'note' => ['nullable', 'string'],
        ]);

        $oldStatus = $application->status;

        $application->update([
            'status'      => $validated['status'],
            'reviewed_by' => Auth::id(),
        ]);

        ApplicationStatusLog::create([
            'application_id' => $application->id,
            'from_status'    => $oldStatus,
            'to_status'      => $validated['status'],
            'changed_by'     => Auth::id(),
            'note'           => $validated['note'] ?? null,
        ]);

        try {
            $application->candidateProfile->user->notify(new ApplicationStatusChanged($application));
        } catch (\Throwable $e) {
            // Notification failure should not block the admin workflow
        }

        return redirect()->back()->with('success', 'Application status updated.');
    }

    /**
     * Stream a candidate document to the admin browser for download or preview.
     * Admins can access any document — no candidate ownership check.
     */
    public function downloadDocument(Document $document): mixed
    {
        if (! Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File not found.');
        }

        $fullPath = Storage::disk('public')->path($document->file_path);
        $mime     = $document->mime_type ?? mime_content_type($fullPath) ?? 'application/octet-stream';
        $filename = $document->original_name ?? basename($document->file_path);

        return response()->file($fullPath, [
            'Content-Type'        => $mime,
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }
}
