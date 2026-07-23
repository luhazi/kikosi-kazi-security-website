@extends('layouts.admin')
@section('title', 'Applicants — Admin')

@section('breadcrumb')
<h1 class="app-content-title">Applicants</h1>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">Applicants</li>
    </ol>
</nav>
@endsection

@section('content')

{{-- FILTER FORM --}}
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body p-3">
        <form action="{{ route('admin.applicants.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Filter by Job</label>
                <select name="job_id" class="form-select form-select-sm">
                    <option value="">All Jobs</option>
                    @foreach($allJobs ?? [] as $j)
                    <option value="{{ $j->id }}" {{ request('job_id') == $j->id ? 'selected' : '' }}>
                        {{ $j->title }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Application Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    <option value="submitted"           {{ request('status') === 'submitted'           ? 'selected' : '' }}>Submitted</option>
                    <option value="under_review"        {{ request('status') === 'under_review'        ? 'selected' : '' }}>Under Review</option>
                    <option value="shortlisted"         {{ request('status') === 'shortlisted'         ? 'selected' : '' }}>Shortlisted</option>
                    <option value="interview_scheduled" {{ request('status') === 'interview_scheduled' ? 'selected' : '' }}>Interview Scheduled</option>
                    <option value="successful"          {{ request('status') === 'successful'          ? 'selected' : '' }}>Successful</option>
                    <option value="rejected"            {{ request('status') === 'rejected'            ? 'selected' : '' }}>Rejected</option>
                    <option value="withdrawn"           {{ request('status') === 'withdrawn'           ? 'selected' : '' }}>Withdrawn</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold mb-1">Search Name / Email</label>
                <input type="text" name="q" class="form-control form-control-sm"
                       placeholder="Search..." value="{{ request('q') }}">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm px-3">
                    <i class="bi bi-funnel-fill me-1"></i>Filter
                </button>
                @if(request()->anyFilled(['job_id','status','q']))
                <a href="{{ route('admin.applicants.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- APPLICANTS TABLE --}}
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white border-0 pt-4 px-4">
        <h5 class="fw-bold mb-0">Candidate Profiles</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3">Candidate Name</th>
                        <th>Email</th>
                        <th>Applications</th>
                        <th>Last Applied</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($candidates as $candidate)
                    <tr>
                        <td class="px-4 fw-semibold">
                            <div class="d-flex align-items-center gap-2">
                                @if($candidate->profile_photo)
                                <img src="{{ asset('storage/' . $candidate->profile_photo) }}"
                                     class="rounded-circle flex-shrink-0"
                                     style="width:36px;height:36px;object-fit:cover;border:1px solid #dee2e6"
                                     alt="">
                                @else
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                                     style="width:36px;height:36px;font-size:.85rem">
                                    {{ strtoupper(substr($candidate->user->name ?? 'U', 0, 1)) }}
                                </div>
                                @endif
                                {{ $candidate->user->name ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="small text-muted">{{ $candidate->user->email ?? '—' }}</td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border">{{ $candidate->applications_count ?? 0 }}</span>
                        </td>
                        <td class="small text-muted">
                            @if(isset($candidate->last_applied_at))
                                {{ \Carbon\Carbon::parse($candidate->last_applied_at)->format('d M Y') }}
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.applicants.show', $candidate) }}"
                               class="btn btn-sm btn-primary">
                                <i class="bi bi-person-lines-fill me-1"></i>View Profile
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-people-fill fs-1 d-block mb-3 opacity-50"></i>
                            No candidates found matching your search criteria.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(isset($candidates) && $candidates->hasPages())
    <div class="card-footer bg-white border-0 py-3 px-4">
        {{ $candidates->appends(request()->query())->links() }}
    </div>
    @endif
</div>

@endsection
