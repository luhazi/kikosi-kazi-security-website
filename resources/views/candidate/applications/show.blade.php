@extends('layouts.candidate')
@section('title', 'Application — ' . $application->job->title)
@section('page-title', 'Application Details')

@section('content')

@php
$allStatuses = [
    'submitted'           => 'Submitted',
    'under_review'        => 'Under Review',
    'shortlisted'         => 'Shortlisted',
    'interview_scheduled' => 'Interview Scheduled',
    'successful'          => 'Successful',
    'rejected'            => 'Rejected',
];
$positiveStatuses = ['submitted','under_review','shortlisted','interview_scheduled','successful'];
$currentStatus = $application->status;
$statusColors = [
    'submitted'=>'secondary','under_review'=>'info','shortlisted'=>'primary',
    'interview_scheduled'=>'warning text-dark','successful'=>'success',
    'rejected'=>'danger','withdrawn'=>'dark',
];
$messages = $application->statusLogs->filter(fn ($l) => filled($l->note));
@endphp

{{-- Back --}}
<div class="mb-4">
    <a href="{{ route('candidate.applications.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back to Applications
    </a>
</div>

<div class="row g-4">

    {{-- LEFT --}}
    <div class="col-lg-7">

        {{-- Job Details Card --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0" style="color:var(--kk-blue)">Job Details</h5>
            </div>
            <div class="card-body p-4">
                <h4 class="fw-bold mb-2">{{ $application->job->title }}</h4>
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge badge-dept px-2 py-1">{{ $application->job->department }}</span>
                    <span class="badge bg-light text-dark border px-2 py-1">
                        <i class="bi bi-geo-alt-fill me-1"></i>{{ $application->job->location }}
                    </span>
                    <span class="badge bg-light text-dark border px-2 py-1">
                        <i class="bi bi-calendar3-fill me-1"></i>Deadline: {{ $application->job->deadline->format('d M Y') }}
                    </span>
                </div>
                <div class="row g-3 text-muted small">
                    <div class="col-6">
                        <strong>Applied:</strong><br>{{ $application->created_at->format('d M Y \a\t H:i') }}
                    </div>
                    <div class="col-6">
                        <strong>Application ID:</strong><br>#{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Cover Letter Card --}}
        @if($application->cover_letter)
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0" style="color:var(--kk-blue)">Cover Letter</h5>
            </div>
            <div class="card-body p-4">
                <p class="text-muted mb-0" style="line-height:1.8;white-space:pre-wrap">{{ $application->cover_letter }}</p>
            </div>
        </div>
        @else
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4 text-center text-muted">
                <i class="bi bi-file-text-fill fs-3 d-block mb-2 opacity-50"></i>
                No cover letter was submitted with this application.
            </div>
        </div>
        @endif

        {{-- Messages / feedback from the recruitment team --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0" style="color:var(--kk-blue)">
                    <i class="bi bi-chat-dots-fill me-2"></i>Messages from Kikosi Kazi
                </h5>
                <small class="text-muted">Updates and notes from the recruitment team about your application.</small>
            </div>
            <div class="card-body p-4">
                @forelse($messages as $log)
                @php
                    $ll = \App\Models\Application::STATUS_LABELS[$log->to_status] ?? ucfirst(str_replace('_',' ',$log->to_status));
                    $cl = $statusColors[$log->to_status] ?? 'secondary';
                @endphp
                <div class="d-flex gap-3 {{ !$loop->last ? 'mb-4 pb-4 border-bottom' : '' }}">
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center flex-shrink-0" style="width:42px;height:42px">
                        <i class="bi bi-person-badge-fill fs-5" style="color:var(--kk-blue)"></i>
                    </div>
                    <div class="flex-fill">
                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                            <span class="fw-semibold">Recruitment Team</span>
                            <span class="badge bg-{{ $cl }} small">{{ $ll }}</span>
                            <span class="text-muted small">· {{ $log->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <p class="text-muted mb-0" style="white-space:pre-wrap;line-height:1.7">{{ $log->note }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-3">
                    <i class="bi bi-inbox-fill fs-3 d-block mb-2 opacity-50"></i>
                    No messages yet. The recruitment team will post updates and feedback here as your application progresses.
                </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- RIGHT: Status Timeline --}}
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0" style="color:var(--kk-blue)">Application Status</h5>
            </div>
            <div class="card-body p-4">

                @php
                    // Determine which statuses to show in timeline
                    // If rejected, show up to rejection point; else show positive statuses
                    $timelineStatuses = $currentStatus === 'rejected'
                        ? array_merge($positiveStatuses, ['rejected'])
                        : $positiveStatuses;

                    $statusOrder = array_values($positiveStatuses);
                    $currentIndex = array_search($currentStatus, $statusOrder);
                @endphp

                <ul class="list-unstyled mb-0">
                    @foreach($positiveStatuses as $i => $status)
                    @php
                        $label = $allStatuses[$status] ?? ucfirst(str_replace('_',' ',$status));
                        $isCurrent = $status === $currentStatus;
                        $isPast = ($currentStatus !== 'rejected') && ($i < ($currentIndex ?? -1));
                        $isFuture = !$isCurrent && !$isPast;
                    @endphp
                    <li class="d-flex align-items-start gap-3 mb-{{ !$loop->last ? '4' : '0' }}">
                        {{-- Circle --}}
                        @if($isCurrent)
                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white flex-shrink-0"
                             style="width:32px;height:32px;background:var(--kk-blue);margin-top:2px">
                            <i class="bi bi-check-lg small"></i>
                        </div>
                        @elseif($isPast)
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:32px;height:32px;background:#ccc;margin-top:2px">
                            <i class="bi bi-check-lg small text-white"></i>
                        </div>
                        @else
                        <div class="rounded-circle border-2 d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:32px;height:32px;border:2px solid #dee2e6;margin-top:2px">
                        </div>
                        @endif
                        {{-- Label --}}
                        <div>
                            <div class="fw-semibold {{ $isCurrent ? '' : 'text-muted' }}"
                                 style="{{ $isCurrent ? 'color:var(--kk-blue)' : '' }}">
                                {{ $label }}
                            </div>
                            @if($isCurrent)
                            <small class="text-muted">Current Status</small>
                            @endif
                        </div>
                    </li>
                    {{-- Connector line --}}
                    @if(!$loop->last)
                    <li style="margin-left:15px;list-style:none">
                        <div style="width:2px;height:24px;background:#dee2e6;margin-bottom:-12px"></div>
                    </li>
                    @endif
                    @endforeach

                    {{-- Rejected state --}}
                    @if($currentStatus === 'rejected')
                    <li class="d-flex align-items-start gap-3 mt-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white flex-shrink-0"
                             style="width:32px;height:32px;background:#dc3545;margin-top:2px">
                            <i class="bi bi-x-lg small"></i>
                        </div>
                        <div>
                            <div class="fw-semibold text-danger">Not Selected</div>
                            <small class="text-muted">Thank you for applying. We encourage you to apply for future positions.</small>
                        </div>
                    </li>
                    @endif
                </ul>
            </div>
        </div>

        {{-- Quick Info Card --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between border-bottom py-2">
                        <span class="text-muted small">Applied Date</span>
                        <span class="fw-semibold small">{{ $application->created_at->format('d M Y') }}</span>
                    </li>
                    <li class="d-flex justify-content-between border-bottom py-2">
                        <span class="text-muted small">Application ID</span>
                        <span class="fw-semibold small">#{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </li>
                    <li class="d-flex justify-content-between py-2">
                        <span class="text-muted small">Current Status</span>
                        @php
                            $sc = [
                                'submitted'=>'secondary','under_review'=>'info','shortlisted'=>'primary',
                                'interview_scheduled'=>'warning text-dark','successful'=>'success',
                                'rejected'=>'danger','withdrawn'=>'dark'
                            ];
                            $cl = $sc[$currentStatus] ?? 'secondary';
                            $ll = $allStatuses[$currentStatus] ?? ucfirst(str_replace('_',' ',$currentStatus));
                        @endphp
                        <span class="badge bg-{{ $cl }} px-3 py-2">{{ $ll }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

</div>

@endsection
