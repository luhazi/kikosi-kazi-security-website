<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\CandidateProfile;
use App\Models\ContactMessage;
use App\Models\Job;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'active_jobs'          => Job::where('status', 'active')->count(),
            'total_candidates'     => CandidateProfile::count(),
            'applications_today'   => Application::whereDate('applied_at', today())->count(),
            'unread_messages'      => ContactMessage::where('is_read', false)->count(),
        ];

        $byStatus = Application::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $recentApplications = Application::with(['job', 'candidate.user'])
            ->latest('applied_at')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'byStatus', 'recentApplications'));
    }
}
