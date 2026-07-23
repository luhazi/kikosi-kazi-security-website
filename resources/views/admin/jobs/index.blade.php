@extends('layouts.admin')
@section('title', 'Job Listings — Admin')

@section('breadcrumb')
<h1 class="app-content-title">Job Listings</h1>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">Jobs</li>
    </ol>
</nav>
@endsection

@section('content')

@php
$statusColours = [
    'draft'    => 'secondary',
    'active'   => 'success',
    'closed'   => 'warning text-dark',
    'archived' => 'dark',
];
$statusLabels = [
    'draft'    => 'Draft',
    'active'   => 'Active',
    'closed'   => 'Closed',
    'archived' => 'Archived',
];
@endphp

{{-- TOP ROW --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 class="fw-bold mb-0">All Job Listings</h4>
    <a href="{{ route('admin.jobs.create') }}" class="btn btn-primary fw-semibold">
        <i class="bi bi-plus-lg me-1"></i>Create Job
    </a>
</div>

{{-- FILTER FORM --}}
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body p-3">
        <form action="{{ route('admin.jobs.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    @foreach($statusLabels as $val => $lbl)
                    <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label small fw-semibold mb-1">Search</label>
                <input type="text" name="q" class="form-control form-control-sm"
                       placeholder="Search title, department, location..." value="{{ request('q') }}">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm px-3 fw-semibold">
                    <i class="bi bi-funnel-fill me-1"></i>Filter
                </button>
                @if(request('status') || request('q'))
                <a href="{{ route('admin.jobs.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- JOBS TABLE --}}
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3">Title</th>
                        <th>Department</th>
                        <th>Location</th>
                        <th>Vacancies</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th>Apps</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jobs as $job)
                    @php
                        $c = $statusColours[$job->status] ?? 'secondary';
                        $l = $statusLabels[$job->status] ?? ucfirst($job->status);
                    @endphp
                    <tr>
                        <td class="px-4 fw-semibold">
                            {{ $job->title }}
                            @if($job->deadline->isPast() && $job->status === 'active')
                            <span class="badge bg-danger ms-1" style="font-size:.65rem">Expired</span>
                            @endif
                        </td>
                        <td class="small text-muted">{{ $job->department }}</td>
                        <td class="small text-muted">{{ $job->location }}</td>
                        <td class="text-center">{{ $job->vacancies }}</td>
                        <td class="small {{ $job->deadline->isPast() ? 'text-danger fw-semibold' : 'text-muted' }}">
                            {{ $job->deadline->format('d M Y') }}
                        </td>
                        <td><span class="badge bg-{{ $c }} px-2 py-1">{{ $l }}</span></td>
                        <td class="text-center">
                            <a href="{{ route('admin.applicants.index', ['job_id' => $job->id]) }}"
                               class="badge bg-light text-dark border text-decoration-none">
                                {{ $job->applications_count ?? $job->applications()->count() }}
                            </a>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('careers.show', $job) }}" target="_blank"
                                   class="btn btn-sm btn-outline-secondary" title="View">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="{{ route('admin.jobs.edit', $job) }}"
                                   class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST"
                                      onsubmit="return confirm('Delete this job listing? All associated applications will also be deleted.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="bi bi-trash-fill text-danger"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-briefcase-fill fs-1 d-block mb-3 opacity-50"></i>
                            No job listings found.
                            <a href="{{ route('admin.jobs.create') }}" class="fw-semibold">Create the first one</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(isset($jobs) && $jobs->hasPages())
    <div class="card-footer bg-white border-0 py-3 px-4">
        {{ $jobs->appends(request()->query())->links() }}
    </div>
    @endif
</div>

@endsection
