@extends('layouts.admin')
@section('title', 'Applicant Profile — Admin')

@section('breadcrumb')
<h1 class="app-content-title">{{ $candidateProfile->user->name ?? 'Candidate' }}</h1>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.applicants.index') }}">Applicants</a></li>
        <li class="breadcrumb-item active">Profile</li>
    </ol>
</nav>
@endsection

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

{{-- Back --}}
<div class="mb-4">
    <a href="{{ route('admin.applicants.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back to Applicants
    </a>
</div>

{{-- ROW 1: Profile Info + Applications --}}
<div class="row g-4 mb-4">

    {{-- Profile Info Card --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body p-4 text-center">
                @if($candidateProfile->profile_photo)
                <img src="{{ asset('storage/' . $candidateProfile->profile_photo) }}"
                     alt="Profile Photo"
                     class="rounded-circle mx-auto d-block mb-3 shadow"
                     style="width:80px;height:80px;object-fit:cover;border:3px solid #dee2e6">
                @else
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold mx-auto mb-3"
                     style="width:80px;height:80px;font-size:2rem">
                    {{ strtoupper(substr($candidateProfile->user->name ?? 'U', 0, 1)) }}
                </div>
                @endif
                <h5 class="fw-bold mb-1">{{ $candidateProfile->user->name ?? 'N/A' }}</h5>
                <p class="text-muted small mb-3">{{ $candidateProfile->user->email ?? 'N/A' }}</p>
                <hr>
                <ul class="list-unstyled text-start small">
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Phone</span>
                        <span class="fw-semibold">{{ $candidateProfile->phone ?? '—' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Gender</span>
                        <span class="fw-semibold">{{ $candidateProfile->gender ?? '—' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Date of Birth</span>
                        <span class="fw-semibold">
                            {{ isset($candidateProfile->date_of_birth) ? $candidateProfile->date_of_birth->format('d M Y') : '—' }}
                        </span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Nationality</span>
                        <span class="fw-semibold">{{ $candidateProfile->nationality ?? '—' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">City</span>
                        <span class="fw-semibold">{{ $candidateProfile->city ?? '—' }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2">
                        <span class="text-muted">Region</span>
                        <span class="fw-semibold">{{ $candidateProfile->region ?? '—' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Applications Card --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0">Applications</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4">Job</th>
                                <th>Status</th>
                                <th>Applied</th>
                                <th>Update Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($candidateProfile->applications as $app)
                            @php
                                $c = $statusColours[$app->status] ?? 'secondary';
                                $l = $statusLabels[$app->status] ?? ucfirst(str_replace('_',' ',$app->status));
                            @endphp
                            <tr>
                                <td class="px-4 fw-semibold small">{{ $app->job->title ?? 'N/A' }}</td>
                                <td><span class="badge bg-{{ $c }} px-2 py-1 small">{{ $l }}</span></td>
                                <td class="small text-muted">{{ $app->created_at->format('d M Y') }}</td>
                                <td>
                                    <form action="{{ route('admin.applicants.status', $app) }}" method="POST"
                                          class="d-flex gap-1 align-items-start">
                                        @csrf
                                        <div>
                                            <select name="status" class="form-select form-select-sm" style="min-width:140px">
                                                @foreach($statusLabels as $val => $lbl)
                                                <option value="{{ $val }}" {{ $app->status === $val ? 'selected' : '' }}>
                                                    {{ $lbl }}
                                                </option>
                                                @endforeach
                                            </select>
                                            <textarea name="note" class="form-control form-control-sm mt-1" rows="2"
                                                      placeholder="Message to candidate (optional) — shown on their application page"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-primary mt-0">Save</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-3 text-muted small">No applications found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ROW 2: Education + Experience --}}
<div class="row g-4 mb-4">

    {{-- Education --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0"><i class="bi bi-mortarboard-fill me-2"></i>Education</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4">Institution</th>
                                <th>Qualification</th>
                                <th>Field</th>
                                <th>Year</th>
                                <th>Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($candidateProfile->education ?? [] as $edu)
                            <tr>
                                <td class="px-4 small fw-semibold">{{ $edu->institution }}</td>
                                <td class="small">{{ $edu->qualification }}</td>
                                <td class="small text-muted">{{ $edu->field_of_study }}</td>
                                <td class="small">{{ $edu->year_completed }}</td>
                                <td class="small">{{ $edu->grade ?? '—' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-3 text-muted small">No education records.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Experience --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0"><i class="bi bi-briefcase-fill me-2"></i>Work Experience</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4">Employer</th>
                                <th>Title</th>
                                <th>From</th>
                                <th>To</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($candidateProfile->experience ?? [] as $exp)
                            <tr>
                                <td class="px-4 small fw-semibold">{{ $exp->employer }}</td>
                                <td class="small">{{ $exp->job_title }}</td>
                                <td class="small text-muted">{{ \Carbon\Carbon::parse($exp->start_date)->format('M Y') }}</td>
                                <td class="small">
                                    @if($exp->end_date)
                                        {{ \Carbon\Carbon::parse($exp->end_date)->format('M Y') }}
                                    @else
                                        <span class="badge bg-success">Current</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-3 text-muted small">No experience records.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ROW 3: Documents --}}
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-header bg-white border-0 pt-4 px-4">
        <h5 class="fw-bold mb-0"><i class="bi bi-folder2-open me-2"></i>Documents</h5>
    </div>
    <div class="card-body p-0">
        @php
        $typeLabels = ['cv'=>'CV / Resume','national_id'=>'National ID','certificate'=>'Certificate','passport_photo'=>'Passport Photo','other'=>'Other'];
        $typeIcons  = ['cv'=>'bi-file-pdf text-danger','national_id'=>'bi-card-text text-primary','certificate'=>'bi-award-fill text-warning','passport_photo'=>'bi-image-fill text-info','other'=>'bi-file-earmark text-secondary'];
        @endphp
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4">Type</th>
                        <th>Filename</th>
                        <th>Size</th>
                        <th>Uploaded</th>
                        <th>Download</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($candidateProfile->documents ?? [] as $doc)
                    <tr>
                        <td class="px-4 small">
                            <i class="bi {{ $typeIcons[$doc->file_type] ?? 'bi-file-earmark text-secondary' }} me-2"></i>
                            {{ $typeLabels[$doc->file_type] ?? ucfirst($doc->file_type) }}
                        </td>
                        <td class="small fw-semibold">{{ $doc->original_name ?? $doc->filename }}</td>
                        <td class="small text-muted">{{ $doc->file_size_human ?? (isset($doc->file_size) ? round($doc->file_size/1024,1).' KB' : '—') }}</td>
                        <td class="small text-muted">{{ $doc->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.documents.download', $doc) }}" target="_blank"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-download"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-3 text-muted small">No documents uploaded.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Bio / Skills --}}
@if($candidateProfile->bio)
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white border-0 pt-4 px-4">
        <h5 class="fw-bold mb-0"><i class="bi bi-person-lines-fill me-2"></i>Bio / Personal Statement</h5>
    </div>
    <div class="card-body p-4">
        <p class="text-muted mb-0" style="line-height:1.8;white-space:pre-wrap">{{ $candidateProfile->bio }}</p>
    </div>
</div>
@endif

@endsection
