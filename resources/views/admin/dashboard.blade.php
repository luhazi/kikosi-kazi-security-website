@extends('layouts.admin')
@section('title', 'Dashboard — Admin')

@section('breadcrumb')
<h1 class="app-content-title">Dashboard</h1>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item active">Home</li>
    </ol>
</nav>
@endsection

@section('content')

{{-- INFO BOXES --}}
<div class="row g-3 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 rounded-3 text-white" style="background:var(--bs-primary)">
            <div class="card-body d-flex align-items-center justify-content-between p-4">
                <div>
                    <div class="display-6 fw-bold">{{ $stats['active_jobs'] ?? 0 }}</div>
                    <div class="small opacity-75">Active Jobs</div>
                </div>
                <i class="bi bi-briefcase-fill opacity-50" style="font-size:3rem"></i>
            </div>
            <div class="card-footer bg-transparent border-0 pb-3 px-4">
                <a href="{{ route('admin.jobs.index') }}" class="text-white-50 small text-decoration-none">
                    View Jobs <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 rounded-3 text-dark" style="background:#17a2b8;color:#fff!important">
            <div class="card-body d-flex align-items-center justify-content-between p-4 text-white">
                <div>
                    <div class="display-6 fw-bold">{{ $stats['total_candidates'] ?? 0 }}</div>
                    <div class="small opacity-75">Total Candidates</div>
                </div>
                <i class="bi bi-people-fill opacity-50" style="font-size:3rem"></i>
            </div>
            <div class="card-footer bg-transparent border-0 pb-3 px-4">
                <a href="{{ route('admin.applicants.index') }}" class="text-white-50 small text-decoration-none">
                    View Candidates <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 rounded-3" style="background:#ffc107">
            <div class="card-body d-flex align-items-center justify-content-between p-4">
                <div>
                    <div class="display-6 fw-bold text-dark">{{ $stats['applications_today'] ?? 0 }}</div>
                    <div class="small text-dark opacity-75">Applications Today</div>
                </div>
                <i class="bi bi-send-fill text-dark opacity-50" style="font-size:3rem"></i>
            </div>
            <div class="card-footer bg-transparent border-0 pb-3 px-4">
                <a href="{{ route('admin.applicants.index') }}" class="text-dark-50 small text-decoration-none" style="color:rgba(0,0,0,0.5)">
                    View Applicants <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 rounded-3 bg-danger text-white">
            <div class="card-body d-flex align-items-center justify-content-between p-4">
                <div>
                    <div class="display-6 fw-bold">{{ $stats['unread_messages'] ?? 0 }}</div>
                    <div class="small opacity-75">Unread Messages</div>
                </div>
                <i class="bi bi-envelope-fill opacity-50" style="font-size:3rem"></i>
            </div>
            <div class="card-footer bg-transparent border-0 pb-3 px-4">
                <span class="text-white-50 small">Check email inbox</span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">

    {{-- Applications by Status --}}
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0">Applications by Status</h5>
            </div>
            <div class="card-body p-4">
                @php
                $statusLabels = [
                    'submitted'           => ['label' => 'Submitted',           'colour' => 'secondary'],
                    'under_review'        => ['label' => 'Under Review',        'colour' => 'info'],
                    'shortlisted'         => ['label' => 'Shortlisted',         'colour' => 'primary'],
                    'interview_scheduled' => ['label' => 'Interview Scheduled', 'colour' => 'warning'],
                    'successful'          => ['label' => 'Successful',          'colour' => 'success'],
                    'rejected'            => ['label' => 'Rejected',            'colour' => 'danger'],
                    'withdrawn'           => ['label' => 'Withdrawn',           'colour' => 'dark'],
                ];
                $total = array_sum(($byStatus ?? collect())->toArray());
                @endphp
                @foreach($statusLabels as $key => $info)
                @php
                    $count = $byStatus[$key] ?? 0;
                    $pct = $total > 0 ? round(($count / $total) * 100) : 0;
                @endphp
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="small fw-semibold">{{ $info['label'] }}</span>
                        <span class="small text-muted">{{ $count }} ({{ $pct }}%)</span>
                    </div>
                    <div class="progress" style="height:8px;border-radius:4px">
                        <div class="progress-bar bg-{{ $info['colour'] }}"
                             role="progressbar" style="width:{{ $pct }}%"
                             aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Recent Applications --}}
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Recent Applications</h5>
                <a href="{{ route('admin.applicants.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3">Candidate</th>
                                <th>Job</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentApplications ?? [] as $app)
                            @php
                                $sc = [
                                    'submitted'=>'secondary','under_review'=>'info','shortlisted'=>'primary',
                                    'interview_scheduled'=>'warning text-dark','successful'=>'success',
                                    'rejected'=>'danger','withdrawn'=>'dark'
                                ];
                                $c = $sc[$app->status] ?? 'secondary';
                            @endphp
                            <tr>
                                <td class="px-4 fw-semibold small">{{ $app->candidate->user->name ?? 'N/A' }}</td>
                                <td class="small text-muted">{{ $app->job->title ?? 'N/A' }}</td>
                                <td><span class="badge bg-{{ $c }} px-2 py-1 small">{{ ucfirst(str_replace('_',' ',$app->status)) }}</span></td>
                                <td class="small text-muted">{{ $app->created_at->format('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted small">No applications yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
