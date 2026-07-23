<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use App\Notifications\ApplicationSubmitted;
use App\Services\DisciplineService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function index(): View
    {
        $applications = Auth::user()
            ->candidateProfile
            ->applications()
            ->with('job')
            ->latest('applied_at')
            ->paginate(15);

        return view('candidate.applications.index', compact('applications'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'job_id'       => ['required', 'exists:jobs,id'],
            'cover_letter' => ['required', 'string', 'min:50', 'max:5000'],
        ]);

        $profile = Auth::user()->candidateProfile;

        // Enforce 100% profile completeness before allowing application
        if (!$profile || $profile->completeness_pct < 100) {
            return redirect()->route('candidate.profile.index')
                ->with('error', 'You must complete your profile 100% before applying for jobs. Please fill in all required fields, add at least one education record and one work experience entry.');
        }

        // Enforce discipline matching — candidate must have a matching qualification
        $job = Job::findOrFail($validated['job_id']);
        $profile->load('education');

        if (!DisciplineService::isEligible($profile, $job->discipline)) {
            $required = DisciplineService::label($job->discipline);
            $detected = DisciplineService::candidateDisciplines($profile);
            $yours    = empty($detected)
                ? 'No matching discipline found in your education records'
                : implode(', ', array_map([DisciplineService::class, 'label'], $detected));

            return redirect()->back()->with(
                'error',
                "You are not eligible to apply for this position. "
                . "This job requires a qualification in <strong>{$required}</strong>. "
                . "Your detected discipline(s): {$yours}. "
                . "Please ensure your education records accurately reflect your qualifications."
            );
        }

        if (Application::where('job_id', $validated['job_id'])->where('candidate_id', $profile->id)->exists()) {
            return redirect()->back()->with('error', 'You have already applied for this position.');
        }

        $app = Application::create([
            'job_id'       => $validated['job_id'],
            'candidate_id' => $profile->id,
            'cover_letter' => $validated['cover_letter'] ?? null,
            'status'       => 'submitted',
            'applied_at'   => now(),
        ]);

        try {
            Auth::user()->notify(new ApplicationSubmitted($app));
        } catch (\Throwable $e) {
            // Notification failure should not block the user flow
        }

        return redirect()->route('candidate.applications.index')
            ->with('success', 'Application submitted successfully!');
    }

    public function show(Application $application): View
    {
        if ($application->candidate_id !== Auth::user()->candidateProfile->id) {
            abort(403);
        }

        $application->load(['job', 'statusLogs' => fn ($q) => $q->latest()]);

        return view('candidate.applications.show', compact('application'));
    }

    public function destroy(Application $application): RedirectResponse
    {
        if ($application->candidate_id !== Auth::user()->candidateProfile->id) {
            abort(403);
        }

        if ($application->status !== 'submitted') {
            return redirect()->back()->with(
                'error',
                'You can only withdraw applications that are still pending review.'
            );
        }

        $application->update(['status' => 'withdrawn']);

        return redirect()->back()->with('success', 'Application withdrawn.');
    }
}
