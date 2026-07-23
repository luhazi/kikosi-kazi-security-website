<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Candidate\ProfileController;
use App\Models\CandidateProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $profile = $user->candidateProfile ?? CandidateProfile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'phone'           => '',
                'nationality'     => 'Tanzanian',
                'address'         => '',
                'city'            => '',
                'region'          => '',
                'gender'          => 'male',
                'date_of_birth'   => now()->subYears(25)->toDateString(),
                'completeness_pct' => 0,
            ]
        );

        // Always sync stored completeness with actual data
        $pct = ProfileController::calculateCompleteness($profile);
        if ($profile->completeness_pct !== $pct) {
            $profile->completeness_pct = $pct;
            $profile->save();
        }

        $applications = $profile->applications()->with('job')->latest('applied_at')->get();

        $stats = [
            'total'       => $applications->count(),
            'pending'     => $applications->whereIn('status', ['submitted', 'under_review'])->count(),
            'shortlisted' => $applications->where('status', 'shortlisted')->count(),
            'interview'   => $applications->where('status', 'interview_scheduled')->count(),
        ];

        $recentApplications = $applications->take(5);

        return view('candidate.dashboard', compact('profile', 'stats', 'recentApplications'));
    }
}
