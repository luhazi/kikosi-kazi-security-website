<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Services\DisciplineService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class JobController extends Controller
{
    public function index(Request $request): View
    {
        $query = Job::withCount('applications')->with('creator');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        $jobs = $query->latest()->paginate(15)->withQueryString();

        return view('admin.jobs.index', compact('jobs'));
    }

    public function create(): View
    {
        return view('admin.jobs.create', [
            'disciplines' => DisciplineService::categories(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $disciplines = array_keys(DisciplineService::categories());

        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'department'  => ['required', 'string', 'max:255'],
            'discipline'   => ['nullable', 'array'],
            'discipline.*' => ['string', 'in:' . implode(',', $disciplines)],
            'location'    => ['required', 'string', 'max:255'],
            'vacancies'   => ['required', 'integer', 'min:1'],
            'description' => ['required', 'string'],
            'requirements'=> ['required', 'string'],
            'deadline'    => ['required', 'date', 'after:today'],
            'status'      => ['required', 'in:draft,active,closed,archived'],
            'job_type'    => ['required', 'in:kikosi_kazi,client'],
            'employment_type' => ['required', 'in:freelance,full_time,internship,part_time,temporary'],
            'client_name' => ['nullable', 'string', 'max:255', 'required_if:job_type,client'],
            'salary_min'  => ['nullable', 'numeric', 'min:0'],
            'salary_max'  => ['nullable', 'numeric', 'min:0', 'gte:salary_min'],
        ]);

        // Normalise selected disciplines → comma-separated keys (NULL = open to all)
        $selected = DisciplineService::jobDisciplines(implode(',', (array) ($validated['discipline'] ?? [])));
        $validated['discipline'] = empty($selected) ? null : implode(',', $selected);

        Job::create(array_merge($validated, ['created_by' => Auth::id()]));

        return redirect()->route('admin.jobs.index')->with('success', 'Job created successfully.');
    }

    public function show(Job $job): View
    {
        return view('admin.jobs.show', [
            'job' => $job->load('applications.candidateProfile.user'),
        ]);
    }

    public function edit(Job $job): View
    {
        return view('admin.jobs.edit', [
            'job'         => $job,
            'disciplines' => DisciplineService::categories(),
        ]);
    }

    public function update(Request $request, Job $job): RedirectResponse
    {
        $disciplines = array_keys(DisciplineService::categories());

        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'department'  => ['required', 'string', 'max:255'],
            'discipline'   => ['nullable', 'array'],
            'discipline.*' => ['string', 'in:' . implode(',', $disciplines)],
            'location'    => ['required', 'string', 'max:255'],
            'vacancies'   => ['required', 'integer', 'min:1'],
            'description' => ['required', 'string'],
            'requirements'=> ['required', 'string'],
            'deadline'    => ['required', 'date'],
            'status'      => ['required', 'in:draft,active,closed,archived'],
            'job_type'    => ['required', 'in:kikosi_kazi,client'],
            'employment_type' => ['required', 'in:freelance,full_time,internship,part_time,temporary'],
            'client_name' => ['nullable', 'string', 'max:255', 'required_if:job_type,client'],
            'salary_min'  => ['nullable', 'numeric', 'min:0'],
            'salary_max'  => ['nullable', 'numeric', 'min:0', 'gte:salary_min'],
        ]);

        // Normalise selected disciplines → comma-separated keys (NULL = open to all)
        $selected = DisciplineService::jobDisciplines(implode(',', (array) ($validated['discipline'] ?? [])));
        $validated['discipline'] = empty($selected) ? null : implode(',', $selected);

        $job->update($validated);

        return redirect()->route('admin.jobs.index')->with('success', 'Job updated successfully.');
    }

    public function destroy(Job $job): RedirectResponse
    {
        $job->delete();

        return redirect()->route('admin.jobs.index')->with('success', 'Job deleted successfully.');
    }
}
