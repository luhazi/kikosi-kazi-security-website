<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CareersController extends Controller
{
    public function index(Request $request): View
    {
        $query = Job::active()->latest('created_at');

        if ($request->filled('q')) {
            $query->where('title', 'like', "%{$request->q}%");
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', "%{$request->location}%");
        }

        // Employment-type checkboxes (freelance, full_time, internship, part_time, temporary)
        if ($request->filled('types')) {
            $types = array_values(array_intersect((array) $request->types, array_keys(Job::EMPLOYMENT_TYPES)));
            if (!empty($types)) {
                $query->where(function ($q) use ($types) {
                    $q->whereIn('employment_type', $types);
                    if (in_array('full_time', $types, true)) {
                        $q->orWhereNull('employment_type'); // legacy rows count as Full Time
                    }
                });
            }
        }

        $jobs = $query->paginate(12)->withQueryString();

        return view('public.careers.index', compact('jobs'));
    }

    public function show(Job $job): View
    {
        // Similar jobs: same department or discipline, still open, excluding this one
        $similar = Job::active()
            ->where('id', '!=', $job->id)
            ->where(function ($q) use ($job) {
                $q->where('department', $job->department);
                if (!empty($job->discipline)) {
                    $q->orWhere('discipline', $job->discipline);
                }
            })
            ->latest('created_at')
            ->take(4)
            ->get();

        // Fallback: if nothing matches, show the latest other open vacancies
        if ($similar->isEmpty()) {
            $similar = Job::active()
                ->where('id', '!=', $job->id)
                ->latest('created_at')
                ->take(4)
                ->get();
        }

        return view('public.careers.show', compact('job', 'similar'));
    }
}
