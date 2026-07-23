@extends('layouts.candidate')
@section('title', 'My Applications')
@section('page-title', 'My Applications')

@section('content')

@php
$statusColours = [
    'submitted'           => 'secondary',
    'under_review'        => 'info',
    'shortlisted'         => 'primary',
    'interview_scheduled' => 'warning text-dark',
    'successful'          => 'success',
    'rejected'            => 'danger',
    'withdrawn'           => 'dark',
];
$statusLabels = [
    'submitted'           => 'Submitted',
    'under_review'        => 'Under Review',
    'shortlisted'         => 'Shortlisted',
    'interview_scheduled' => 'Interview Scheduled',
    'successful'          => 'Successful',
    'rejected'            => 'Rejected',
    'withdrawn'           => 'Withdrawn',
];
@endphp

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h3 class="fw-bold mb-1" style="color:var(--kk-blue)">My Applications</h3>
        <p class="text-muted mb-0 small">Track the status of all your job applications.</p>
    </div>
    <a href="{{ route('careers.index') }}" class="btn btn-sm fw-semibold" style="background:var(--kk-blue);color:#fff">
        <i class="bi bi-briefcase-fill me-1"></i>Browse Jobs
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3">Job Title</th>
                        <th>Department</th>
                        <th>Applied</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $app)
                    @php
                        $colour = $statusColours[$app->status] ?? 'secondary';
                        $label  = $statusLabels[$app->status]  ?? ucfirst(str_replace('_',' ',$app->status));
                    @endphp
                    <tr>
                        <td class="px-4 fw-semibold">
                            <a href="{{ route('careers.show', $app->job) }}" class="text-decoration-none" style="color:var(--kk-blue)">
                                {{ $app->job->title ?? 'N/A' }}
                            </a>
                        </td>
                        <td class="text-muted small">{{ $app->job->department ?? '—' }}</td>
                        <td class="text-muted small">{{ $app->created_at->format('d M Y') }}</td>
                        <td>
                            <span class="badge bg-{{ $colour }} px-3 py-2">{{ $label }}</span>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('candidate.applications.show', $app) }}"
                                   class="btn btn-sm btn-outline-primary">View</a>
                                @if($app->status === 'submitted')
                                <form action="{{ route('candidate.applications.destroy', $app) }}" method="POST"
                                      onsubmit="return confirm('Are you sure you want to withdraw this application? This cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Withdraw</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox-fill fs-1 d-block mb-3 opacity-50"></i>
                            <p class="mb-2">You have not submitted any applications yet.</p>
                            <a href="{{ route('careers.index') }}" class="fw-semibold text-decoration-none" style="color:var(--kk-blue)">
                                Browse open jobs and apply today
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($applications->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $applications->links() }}
</div>
@endif

@endsection
