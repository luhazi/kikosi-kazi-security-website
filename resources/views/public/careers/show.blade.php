@extends('layouts.public')
@section('title', $job->title . ' — Kikosi Kazi Security')
@section('meta_description', 'Apply for the ' . $job->title . ' role at Kikosi Kazi Security. View responsibilities, requirements and submit your application online through our candidate portal.')
@section('og_title', $job->title . ' — Job Opening at Kikosi Kazi Security')
@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "JobPosting",
    "title": "{{ $job->title }}",
    "datePosted": "{{ optional($job->created_at)->toDateString() }}",
    "employmentType": "FULL_TIME",
    "hiringOrganization": {
        "@@type": "Organization",
        "name": "Kikosi Kazi Security",
        "sameAs": "{{ request()->root() }}"
    },
    "jobLocation": {
        "@@type": "Place",
        "address": { "@@type": "PostalAddress", "addressLocality": "Dar es Salaam", "addressCountry": "TZ" }
    }
}
</script>
@endpush
@section('navbar-class', 'solid')

@php
use App\Services\DisciplineService;

$disciplineLabel  = DisciplineService::label($job->discipline);
$isOpenDiscipline = empty($job->discipline) || $job->discipline === 'any';

// Discipline eligibility check (only for logged-in candidates)
$candidateEligible = true;
$candidateDisciplines = [];
if (auth()->check() && auth()->user()->hasRole('candidate')) {
    $cProfile = auth()->user()->candidateProfile;
    if ($cProfile) {
        $cProfile->load('education');
        $candidateDisciplines = DisciplineService::candidateDisciplines($cProfile);
        $candidateEligible    = DisciplineService::isEligible($cProfile, $job->discipline);
    }
}
@endphp

@section('content')

{{-- MINI HERO / BREADCRUMB --}}
<section class="py-4" style="background:linear-gradient(135deg,#0D47A1 0%,#1565C0 100%)">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('careers.index') }}" class="text-white-50 text-decoration-none">Careers</a></li>
                <li class="breadcrumb-item active text-white">{{ $job->title }}</li>
            </ol>
        </nav>
    </div>
</section>

<div class="container section-py">
    <div class="row g-4">

        {{-- LEFT COLUMN: Job Details --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <div class="card-body">
                    <h2 class="fw-bold mb-3" style="color:var(--kk-blue)">{{ $job->title }}</h2>

                    {{-- Job Source Badge --}}
                    @if(isset($job->job_type) && $job->job_type === 'client')
                    <div class="mb-3">
                        <span class="badge rounded-pill px-3 py-2" style="background:#E3F2FD;color:#1565C0;font-size:.85rem">
                            <i class="bi bi-building-fill me-1"></i>Hiring for: <strong>{{ $job->client_name ?? 'Client Company' }}</strong>
                        </span>
                        <span class="text-muted small ms-2">— Posted by Kikosi Kazi on behalf of this employer</span>
                    </div>
                    @else
                    <div class="mb-3">
                        <span class="badge rounded-pill px-3 py-2" style="background:#E8F5E9;color:#2E7D32;font-size:.85rem">
                            <i class="bi bi-star-fill me-1"></i>Direct Hire — Kikosi Kazi Security
                        </span>
                    </div>
                    @endif

                    {{-- Badge Row --}}
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <span class="badge badge-dept px-3 py-2 fs-6">
                            <i class="bi bi-briefcase-fill me-1"></i>{{ $job->department }}
                        </span>
                        <span class="badge px-3 py-2 fs-6" style="background:#FFF7E0;color:#8a6d1a">
                            <i class="bi bi-clock-fill me-1"></i>{{ $job->employmentTypeLabel() }}
                        </span>
                        <span class="badge bg-light text-dark border px-3 py-2 fs-6">
                            <i class="bi bi-geo-alt-fill me-1 text-primary"></i>{{ $job->location }}
                        </span>
                        <span class="badge bg-light text-dark border px-3 py-2 fs-6">
                            <i class="bi bi-people-fill me-1 text-primary"></i>{{ $job->vacancies }} Position{{ $job->vacancies != 1 ? 's' : '' }}
                        </span>
                        <span class="badge {{ $job->deadline->isPast() ? 'bg-danger' : 'bg-light text-dark border' }} px-3 py-2 fs-6">
                            <i class="bi bi-calendar3-fill me-1"></i>
                            Deadline: {{ $job->deadline->format('d M Y') }}
                            @if($job->deadline->isPast()) (Closed) @endif
                        </span>
                    </div>

                    {{-- Job Description --}}
                    <h5 class="fw-bold mb-3" style="color:var(--kk-blue)">Job Description</h5>
                    <div class="text-muted mb-4" style="line-height:1.8">
                        {!! $job->description !!}
                    </div>

                    {{-- Requirements --}}
                    <h5 class="fw-bold mb-3" style="color:var(--kk-blue)">Requirements</h5>
                    <div class="text-muted" style="line-height:1.8">
                        {!! $job->requirements !!}
                    </div>
                </div>
            </div>

            {{-- Salary (if set) --}}
            @if($job->salary_min || $job->salary_max)
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-2" style="color:var(--kk-blue)">Salary Range</h5>
                    <p class="text-muted mb-0">
                        @if($job->salary_min && $job->salary_max)
                            TZS {{ number_format($job->salary_min) }} — TZS {{ number_format($job->salary_max) }} per month
                        @elseif($job->salary_min)
                            From TZS {{ number_format($job->salary_min) }} per month
                        @else
                            Up to TZS {{ number_format($job->salary_max) }} per month
                        @endif
                    </p>
                </div>
            </div>
            @endif

            <a href="{{ route('careers.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to All Jobs
            </a>
        </div>

        {{-- RIGHT COLUMN: Apply Sidebar --}}
        <div class="col-lg-4">
            <div class="sticky-top" style="top:80px">

                {{-- Apply Card --}}
                <div class="card border-0 shadow rounded-4 mb-4 overflow-hidden">
                    <div class="card-header py-3 fw-bold text-white" style="background:var(--kk-blue)">
                        <i class="bi bi-send-fill me-2"></i>Apply for this Position
                    </div>
                    <div class="card-body p-4">
                        @if($job->deadline->isPast())
                        <div class="alert alert-warning mb-0">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Applications Closed.</strong> The deadline for this position has passed.
                        </div>
                        @else

                        {{-- Discipline badge --}}
                        @if(!$isOpenDiscipline)
                        <div class="mb-3 p-3 rounded-3" style="background:#EEF2FF;border:1px solid #C7D2FE">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-mortarboard-fill mt-1" style="color:var(--kk-blue);flex-shrink:0"></i>
                                <div>
                                    <div class="fw-semibold small" style="color:var(--kk-blue)">Required Discipline</div>
                                    <div class="small text-muted">{{ $disciplineLabel }}</div>
                                </div>
                            </div>
                        </div>
                        @endif

                            @auth
                                @if(Auth::user()->hasRole('candidate'))

                                {{-- Discipline eligibility status for the logged-in candidate --}}
                                @if(!$isOpenDiscipline)
                                    @if($candidateEligible)
                                    <div class="alert alert-success py-2 px-3 small mb-3">
                                        <i class="bi bi-check-circle-fill me-1"></i>
                                        <strong>Your qualification matches</strong> the requirements for this position.
                                    </div>
                                    @else
                                    <div class="alert alert-danger py-2 px-3 small mb-3">
                                        <i class="bi bi-x-circle-fill me-1"></i>
                                        <strong>Qualification mismatch.</strong> This position requires <strong>{{ $disciplineLabel }}</strong>.
                                        @if(!empty($candidateDisciplines))
                                        Your qualification(s): {{ implode(', ', array_map([App\Services\DisciplineService::class, 'label'], $candidateDisciplines)) }}.
                                        @else
                                        No matching discipline found in your education records.
                                        @endif
                                        <a href="{{ route('candidate.profile.index') }}" class="alert-link d-block mt-1">Update your education records →</a>
                                    </div>
                                    @endif
                                @endif

                                @if($candidateEligible)
                                <form action="{{ route('candidate.applications.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="job_id" value="{{ $job->id }}">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Cover Letter <span class="text-danger fw-normal">*</span></label>
                                        <textarea name="cover_letter" class="form-control" rows="6" required
                                            placeholder="Tell us why you are the ideal candidate for this role..."></textarea>
                                    </div>
                                    <p class="text-muted small mb-3">
                                        <i class="bi bi-info-circle-fill me-1"></i>
                                        Your saved profile information will be submitted automatically.
                                    </p>
                                    <button type="submit" class="btn btn-kk w-100 fw-semibold py-2">
                                        <i class="bi bi-send-fill me-2"></i>Submit Application
                                    </button>
                                </form>
                                @else
                                <button class="btn btn-secondary w-100 fw-semibold py-2 disabled" disabled>
                                    <i class="bi bi-slash-circle-fill me-2"></i>Not Eligible to Apply
                                </button>
                                @endif

                                @else
                                <div class="alert alert-info small mb-3">
                                    <i class="bi bi-info-circle-fill me-2"></i>
                                    You're signed in with an account that isn't set up as a job seeker
                                    (for example, an administrator or staff account), so it can't apply for vacancies.
                                </div>
                                <p class="small text-muted mb-3">
                                    To apply for this position, sign out and log in — or register — with a job-seeker account.
                                </p>
                                <form method="POST" action="{{ route('logout') }}" class="mb-0">
                                    @csrf
                                    <button type="submit" class="btn btn-kk w-100 fw-semibold">
                                        <i class="bi bi-box-arrow-right me-2"></i>Sign out to apply
                                    </button>
                                </form>
                                @endif
                            @else
                            <p class="text-muted mb-3">You must be logged in to apply for this position.</p>
                            <a href="{{ route('login') }}" class="btn btn-kk w-100 fw-semibold mb-2">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login to Apply
                            </a>
                            <p class="text-center small text-muted mb-0">
                                New here?
                                <a href="{{ route('register') }}" class="fw-semibold text-decoration-none" style="color:var(--kk-blue)">Register an Account</a>
                            </p>
                            @endauth
                        @endif
                    </div>
                </div>

                {{-- Job Summary Card --}}
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header py-3 fw-bold" style="background:#E8F0FE;color:var(--kk-blue)">
                        <i class="bi bi-info-circle-fill me-2"></i>Job Summary
                    </div>
                    <div class="card-body p-4">
                        <ul class="list-unstyled mb-0">
                            <li class="d-flex justify-content-between border-bottom py-2">
                                <span class="text-muted small">Job Ref</span>
                                <span class="fw-semibold small">KK-{{ str_pad($job->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </li>
                            <li class="d-flex justify-content-between border-bottom py-2">
                                <span class="text-muted small">Vacancies</span>
                                <span class="fw-semibold small">{{ $job->vacancies }}</span>
                            </li>
                            <li class="d-flex justify-content-between border-bottom py-2">
                                <span class="text-muted small">Department</span>
                                <span class="fw-semibold small">{{ $job->department }}</span>
                            </li>
                            <li class="d-flex justify-content-between border-bottom py-2">
                                <span class="text-muted small">Job Type</span>
                                <span class="fw-semibold small" style="color:var(--kk-blue)">
                                    <i class="bi bi-clock-fill me-1"></i>{{ $job->employmentTypeLabel() }}
                                </span>
                            </li>
                            <li class="d-flex justify-content-between border-bottom py-2">
                                <span class="text-muted small">Discipline</span>
                                <span class="fw-semibold small" style="color:var(--kk-blue)">
                                    <i class="bi bi-mortarboard-fill me-1"></i>{{ $disciplineLabel }}
                                </span>
                            </li>
                            <li class="d-flex justify-content-between border-bottom py-2">
                                <span class="text-muted small">Location</span>
                                <span class="fw-semibold small">{{ $job->location }}</span>
                            </li>
                            <li class="d-flex justify-content-between border-bottom py-2">
                                <span class="text-muted small">Deadline</span>
                                <span class="fw-semibold small {{ $job->deadline->isPast() ? 'text-danger' : '' }}">
                                    {{ $job->deadline->format('d M Y') }}
                                </span>
                            </li>
                            <li class="d-flex justify-content-between border-bottom py-2">
                                <span class="text-muted small">Posted</span>
                                <span class="fw-semibold small">{{ $job->created_at->diffForHumans() }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-2">
                                <span class="text-muted small">Employer</span>
                                <span class="fw-semibold small">
                                    @if(isset($job->job_type) && $job->job_type === 'client')
                                        {{ $job->client_name ?? 'Client' }}
                                    @else
                                        Kikosi Kazi
                                    @endif
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Share This Job --}}
                @php
                    $shareUrl   = urlencode(request()->fullUrl());
                    $shareText  = urlencode($job->title . ' at ' . (($job->job_type ?? '') === 'client' ? ($job->client_name ?? 'Kikosi Kazi') : 'Kikosi Kazi') . ' — ' . $job->location);
                @endphp
                <div class="card border-0 shadow-sm rounded-4 mt-4">
                    <div class="card-header py-3 fw-bold text-center" style="background:#E8F0FE;color:var(--kk-blue)">
                        <i class="bi bi-share-fill me-2"></i>Share This Job
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                            <a href="https://x.com/intent/tweet?text={{ $shareText }}&url={{ $shareUrl }}" target="_blank" rel="noopener"
                               class="share-btn" style="background:#000" aria-label="Share on X" title="Share on X"><i class="bi bi-twitter-x"></i></a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener"
                               class="share-btn" style="background:#1877F2" aria-label="Share on Facebook" title="Share on Facebook"><i class="bi bi-facebook"></i></a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" target="_blank" rel="noopener"
                               class="share-btn" style="background:#0A66C2" aria-label="Share on LinkedIn" title="Share on LinkedIn"><i class="bi bi-linkedin"></i></a>
                            <a href="https://wa.me/?text={{ $shareText }}%20{{ $shareUrl }}" target="_blank" rel="noopener"
                               class="share-btn" style="background:#25D366" aria-label="Share on WhatsApp" title="Share on WhatsApp"><i class="bi bi-whatsapp"></i></a>
                            <a href="mailto:?subject={{ urlencode('Job opportunity: '.$job->title) }}&body={{ $shareText }}%20{{ $shareUrl }}"
                               class="share-btn" style="background:#EA4335" aria-label="Share by Email" title="Share by Email"><i class="bi bi-envelope-fill"></i></a>
                            <button type="button" class="share-btn" style="background:var(--kk-blue)" aria-label="Copy link" title="Copy link"
                                    onclick="navigator.clipboard.writeText('{{ request()->fullUrl() }}').then(()=>{this.innerHTML='<i class=\'bi bi-check-lg\'></i>';setTimeout(()=>{this.innerHTML='<i class=\'bi bi-link-45deg\'></i>';},1500);})">
                                <i class="bi bi-link-45deg"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Similar Jobs --}}
                @if(isset($similar) && $similar->count())
                <div class="card border-0 shadow-sm rounded-4 mt-4">
                    <div class="card-header py-3 fw-bold" style="background:var(--kk-blue);color:#fff">
                        <i class="bi bi-briefcase-fill me-2"></i>Similar Jobs
                    </div>
                    <div class="list-group list-group-flush rounded-bottom-4">
                        @foreach($similar as $s)
                        <a href="{{ route('careers.show', $s) }}" class="list-group-item list-group-item-action py-3 px-4">
                            <div class="fw-semibold mb-1" style="color:var(--kk-blue)">{{ $s->title }}</div>
                            <div class="small text-muted">
                                <i class="bi bi-geo-alt-fill me-1 text-primary"></i>{{ $s->location }}
                                <span class="mx-1">·</span>
                                <i class="bi bi-briefcase me-1 text-primary"></i>{{ $s->department }}
                            </div>
                            <div class="small mt-1 {{ $s->deadline->isPast() ? 'text-danger' : 'text-muted' }}">
                                <i class="bi bi-calendar3 me-1"></i>Deadline: {{ $s->deadline->format('d M Y') }}
                            </div>
                        </a>
                        @endforeach
                    </div>
                    <div class="card-footer bg-white border-0 text-center py-3">
                        <a href="{{ route('careers.index') }}" class="fw-semibold text-decoration-none" style="color:var(--kk-blue)">
                            View all vacancies <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                @endif

            </div>
        </div>

    </div>
</div>

<style>
.share-btn {
    display:inline-flex; align-items:center; justify-content:center;
    width:42px; height:42px; border-radius:50%;
    color:#fff; border:none; font-size:1.05rem; text-decoration:none;
    transition:transform .15s, opacity .2s;
}
.share-btn:hover { transform:translateY(-2px); opacity:.9; color:#fff; }
</style>

@endsection
