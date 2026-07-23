@extends('layouts.public')
@section('title', 'Careers — Kikosi Kazi Security')
@section('meta_description', 'Build your career with Kikosi Kazi Security, Tanzania\'s leading integrated services company. Browse open vacancies in security, human capital, insurance and facility management and apply online.')
@section('og_title', 'Careers at Kikosi Kazi Security — Join Tanzania\'s Leading Integrated Services Team')
@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ request()->root() }}" },
        { "@@type": "ListItem", "position": 2, "name": "Careers", "item": "{{ request()->url() }}" }
    ]
}
</script>
@endpush
@section('navbar-class', 'solid')

@section('content')

{{-- HERO --}}
<section class="py-5" style="background:linear-gradient(135deg,#0D47A1 0%,#1565C0 100%);min-height:260px;display:flex;align-items:center">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active text-white">Careers</li>
            </ol>
        </nav>
        <h1 class="display-5 fw-bold text-white mb-2">Join Our Team</h1>
        <p class="lead text-white-50 mb-0">Build your career with Tanzania's leading integrated services company. We are always looking for talented, dedicated people to join the Kikosi Kazi family.</p>
    </div>
</section>

{{-- FILTER BAR --}}
<section class="py-4 bg-white shadow-sm">
    <div class="container">
        <form action="{{ route('careers.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label small fw-semibold text-muted mb-1">Search Job Title</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="q" class="form-control border-start-0" placeholder="e.g. Security Guard, HR Officer..." value="{{ request('q') }}">
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold text-muted mb-1">Location</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-geo-alt-fill text-muted"></i></span>
                    <input type="text" name="location" class="form-control border-start-0" placeholder="e.g. Dar es Salaam, Arusha..." value="{{ request('location') }}">
                </div>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-kk flex-fill fw-semibold">
                    <i class="bi bi-funnel-fill me-1"></i>Filter
                </button>
                @if(request('q') || request('location') || request('types'))
                <a href="{{ route('careers.index') }}" class="btn btn-outline-secondary" title="Clear filters">
                    <i class="bi bi-x-lg"></i>
                </a>
                @endif
            </div>

            {{-- Job type checkboxes --}}
            <div class="col-12 mt-2">
                <div class="d-flex flex-wrap align-items-center gap-3 pt-3 border-top">
                    <span class="small fw-semibold text-muted"><i class="bi bi-briefcase-fill me-1" style="color:var(--kk-blue)"></i>Job Type:</span>
                    @foreach(\App\Models\Job::EMPLOYMENT_TYPES as $key => $label)
                        @php $checked = !request()->has('types') || in_array($key, (array) request('types')); @endphp
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="types[]" value="{{ $key }}"
                                   id="type_{{ $key }}" {{ $checked ? 'checked' : '' }} onchange="this.form.submit()">
                            <label class="form-check-label small" for="type_{{ $key }}">{{ $label }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
        </form>
    </div>
</section>

{{-- JOB ALERTS BANNER (guests) --}}
@guest
<section class="py-3" style="background:#FFF7E0;border-bottom:1px solid #F3E6B8">
    <div class="container d-flex flex-wrap align-items-center justify-content-center gap-2 text-center">
        <span class="small fw-semibold" style="color:#8a6d1a">
            <i class="bi bi-bell-fill me-1"></i>Never miss an opportunity — get new vacancies sent to your inbox.
        </span>
        <a href="{{ route('register') }}" class="btn btn-kk btn-sm px-3">
            <i class="bi bi-envelope-fill me-1"></i>Register for Job Alerts
        </a>
    </div>
</section>
@endguest

{{-- JOBS GRID --}}
<section class="section-py bg-light-blue">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title mb-0">
                @if(request('q') || request('location'))
                    Search Results
                @else
                    Current <span>Vacancies</span>
                @endif
            </h2>
            @auth
                @if(auth()->user()->hasRole('candidate'))
                <a href="{{ route('candidate.dashboard') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-person-circle me-1"></i>My Portal
                </a>
                @endif
            @else
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-box-arrow-in-right me-1"></i>Candidate Login
                </a>
                <a href="{{ route('register') }}" class="btn btn-kk btn-sm">
                    <i class="bi bi-person-plus-fill me-1"></i>Create Account
                </a>
            </div>
            @endauth
        </div>

        @php
        use App\Services\DisciplineService;
        // Candidate disciplines — resolved once for the whole listing
        $myDisciplines = [];
        if (auth()->check() && auth()->user()->hasRole('candidate')) {
            $cp = auth()->user()->candidateProfile;
            if ($cp) {
                $cp->load('education');
                $myDisciplines = DisciplineService::candidateDisciplines($cp);
            }
        }
        @endphp

        <div class="row g-4">
            @forelse($jobs as $job)
            @php
                $reqDisc          = DisciplineService::jobDisciplines($job->discipline);
                $isOpen           = empty($reqDisc);
                $disciplineLabel  = DisciplineService::label($job->discipline);
                // Compact label for the small card badge: first discipline + "+N"
                $discShort = count($reqDisc)
                    ? DisciplineService::label($reqDisc[0]) . (count($reqDisc) > 1 ? '  +'.(count($reqDisc) - 1) : '')
                    : '';
                // Per-job eligibility (candidate matches ANY required discipline)
                $eligible = true;
                if (!$isOpen && auth()->check() && auth()->user()->hasRole('candidate')) {
                    $eligible = count(array_intersect($reqDisc, $myDisciplines)) > 0;
                }
            @endphp
            <div class="col-lg-4 col-md-6">
                <div class="card card-service h-100 p-4 d-flex flex-column">
                    <div class="card-body d-flex flex-column p-0">
                        <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                            <h6 class="fw-bold mb-0">{{ $job->title }}</h6>
                            @if(isset($job->job_type) && $job->job_type === 'client')
                            <span class="badge rounded-pill text-nowrap" style="background:#E3F2FD;color:#1565C0;font-size:.7rem">
                                <i class="bi bi-building-fill me-1"></i>{{ $job->client_name ?? 'Client' }}
                            </span>
                            @else
                            <span class="badge rounded-pill text-nowrap" style="background:#E8F5E9;color:#2E7D32;font-size:.7rem">
                                <i class="bi bi-star-fill me-1"></i>Kikosi Kazi
                            </span>
                            @endif
                        </div>
                        <div class="d-flex flex-wrap gap-1 mb-2">
                            <span class="badge badge-dept align-self-start px-2 py-1">{{ $job->department }}</span>
                            <span class="badge align-self-start px-2 py-1" style="background:#FFF7E0;color:#8a6d1a;font-size:.72rem">
                                <i class="bi bi-clock-fill me-1"></i>{{ $job->employmentTypeLabel() }}
                            </span>
                        </div>

                        {{-- Discipline badge (compact + truncated so it never overflows the card) --}}
                        @if(!$isOpen)
                        <span class="badge mb-3 align-self-start d-inline-flex align-items-center"
                              title="{{ $disciplineLabel }}"
                              style="max-width:100%;background:{{ $eligible ? '#EEF2FF' : '#FEE2E2' }};color:{{ $eligible ? '#3730A3' : '#991B1B' }};font-size:.7rem;padding:.35em .6em">
                            <i class="bi bi-mortarboard-fill me-1 flex-shrink-0"></i>
                            <span class="text-truncate">{{ $discShort }}</span>
                            @if(auth()->check() && auth()->user()->hasRole('candidate'))
                                @if($eligible)
                                <i class="bi bi-check-circle-fill ms-1 text-success flex-shrink-0"></i>
                                @else
                                <i class="bi bi-x-circle-fill ms-1 text-danger flex-shrink-0"></i>
                                @endif
                            @endif
                        </span>
                        @endif

                        <ul class="list-unstyled small text-muted mb-3">
                            <li class="mb-1">
                                <i class="bi bi-geo-alt-fill me-2 text-primary"></i>{{ $job->location }}
                                <span class="mx-2 text-muted">|</span>
                                <i class="bi bi-people-fill me-1 text-primary"></i>{{ $job->vacancies }} position{{ $job->vacancies != 1 ? 's' : '' }}
                            </li>
                            <li>
                                <i class="bi bi-calendar3-fill me-2 {{ $job->deadline->isPast() ? 'text-danger' : 'text-primary' }}"></i>
                                Deadline:
                                <span class="{{ $job->deadline->isPast() ? 'text-danger fw-semibold' : '' }}">
                                    {{ $job->deadline->format('d M Y') }}
                                    @if($job->deadline->isPast()) (Closed) @endif
                                </span>
                            </li>
                        </ul>
                        <div class="mt-auto d-flex gap-2">
                            <a href="{{ route('careers.show', $job) }}" class="btn btn-outline-primary btn-sm flex-fill">
                                Details
                            </a>
                            @if(!$job->deadline->isPast())
                                @if(!$isOpen && auth()->check() && auth()->user()->hasRole('candidate') && !$eligible)
                                <span class="btn btn-sm flex-fill fw-semibold disabled" style="background:#FEE2E2;color:#991B1B;border:1px solid #FECACA" title="Your qualification does not match this position">
                                    <i class="bi bi-slash-circle-fill me-1"></i>Not Eligible
                                </span>
                                @else
                                <a href="{{ route('careers.show', $job) }}" class="btn btn-amber btn-sm flex-fill fw-semibold">
                                    Apply Now
                                </a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                    <i class="bi bi-briefcase-fill display-4 text-muted mb-3"></i>
                    <h5 class="fw-bold text-muted mb-2">No vacancies at the moment</h5>
                    @if(request('q') || request('location'))
                    <p class="text-muted mb-3">No jobs match your search criteria. Try different keywords or clear your filters.</p>
                    <a href="{{ route('careers.index') }}" class="btn btn-outline-primary">Clear Filters</a>
                    @else
                    <p class="text-muted mb-3">We will be announcing new vacancies soon. Register now to be among the first to know.</p>
                    <a href="{{ route('register') }}" class="btn btn-kk px-4">Register for Alerts</a>
                    @endif
                </div>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($jobs->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $jobs->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</section>

{{-- WHY JOIN US --}}
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Why Work at <span>Kikosi Kazi?</span></h2>
        </div>
        <div class="row g-4 text-center">
            <div class="col-md-3">
                <i class="bi bi-graph-up-arrow fs-1 mb-3" style="color:var(--kk-blue)"></i>
                <h6 class="fw-bold">Career Growth</h6>
                <p class="text-muted small">Clear career paths and promotion opportunities across all our divisions.</p>
            </div>
            <div class="col-md-3">
                <i class="bi bi-mortarboard-fill fs-1 mb-3" style="color:var(--kk-blue)"></i>
                <h6 class="fw-bold">Training &amp; Development</h6>
                <p class="text-muted small">Continuous professional training and skills development programmes.</p>
            </div>
            <div class="col-md-3">
                <i class="bi bi-heart-pulse-fill fs-1 mb-3" style="color:var(--kk-blue)"></i>
                <h6 class="fw-bold">Staff Benefits</h6>
                <p class="text-muted small">Competitive salaries, NSSF, WCF cover and performance bonuses.</p>
            </div>
            <div class="col-md-3">
                <i class="bi bi-people-fill fs-1 mb-3" style="color:var(--kk-blue)"></i>
                <h6 class="fw-bold">Great Team</h6>
                <p class="text-muted small">Join a diverse, professional and supportive team of over 500 staff nationwide.</p>
            </div>
        </div>
    </div>
</section>

@endsection
