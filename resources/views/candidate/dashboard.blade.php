@extends('layouts.candidate')
@section('title', 'Dashboard — Candidate Portal')
@section('page-title', 'Dashboard')

@section('content')

{{-- TOP ROW: Welcome + Profile Completeness Ring --}}
<div class="row g-4 mb-4 align-items-stretch">

    {{-- Welcome Card --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 h-100"
             style="background:linear-gradient(135deg,#0D47A1 0%,#1565C0 60%,#1976D2 100%);overflow:hidden">
            <div class="card-body p-4 position-relative">
                {{-- Decorative circles --}}
                <div style="position:absolute;top:-40px;right:-40px;width:180px;height:180px;border-radius:50%;background:rgba(255,255,255,.06)"></div>
                <div style="position:absolute;bottom:-30px;right:60px;width:100px;height:100px;border-radius:50%;background:rgba(255,255,255,.04)"></div>

                <div class="position-relative">
                    <p class="text-white-50 mb-1 small text-uppercase fw-semibold tracking-wide">Welcome back</p>
                    <h3 class="fw-bold text-white mb-2">{{ Auth::user()->name }}</h3>
                    <p class="text-white-50 mb-4" style="max-width:480px">
                        Manage your job applications, keep your profile up to date and download your CV — all from right here.
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('careers.index') }}" class="btn fw-semibold px-4 py-2"
                           style="background:#FFB300;color:#000;border:none">
                            <i class="bi bi-briefcase-fill me-2"></i>Browse Open Jobs
                        </a>
                        <a href="{{ route('candidate.profile.index') }}" class="btn btn-outline-light fw-semibold px-4 py-2">
                            <i class="bi bi-person-fill me-2"></i>My Profile
                        </a>
                        @if(isset($profile) && $profile->completeness_pct >= 100)
                        <a href="{{ route('candidate.cv.show') }}" class="btn btn-outline-light fw-semibold px-4 py-2"
                           target="_blank">
                            <i class="bi bi-file-earmark-pdf-fill me-2"></i>Download CV
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Profile Completeness Ring --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 text-center p-4">
            <h6 class="fw-bold text-muted text-uppercase small mb-3">Profile Completeness</h6>

            @php
                $pct  = isset($profile) ? $profile->completeness_pct : 0;
                $dash = 283; // 2 * pi * r (r=45)
                $fill = round(($pct / 100) * $dash);
                $col  = $pct >= 100 ? '#2E7D32' : ($pct >= 60 ? '#FFB300' : '#D32F2F');
            @endphp

            <div class="position-relative d-inline-block mb-3" style="width:130px;height:130px;margin:0 auto">
                <svg viewBox="0 0 100 100" style="transform:rotate(-90deg);width:100%;height:100%">
                    <circle cx="50" cy="50" r="45" fill="none" stroke="#E0E0E0" stroke-width="8"/>
                    <circle cx="50" cy="50" r="45" fill="none"
                            stroke="{{ $col }}" stroke-width="8"
                            stroke-linecap="round"
                            stroke-dasharray="{{ $fill }} {{ $dash }}"
                            style="transition:stroke-dasharray .6s ease"/>
                </svg>
                <div class="position-absolute top-50 start-50 translate-middle text-center">
                    <div class="fw-bold" style="font-size:1.6rem;color:{{ $col }}">{{ $pct }}%</div>
                    <div class="text-muted" style="font-size:.65rem">Complete</div>
                </div>
            </div>

            @if($pct < 100)
            <p class="text-muted small mb-3">
                Fill in all personal details, add at least one education record and one work experience entry to reach 100%.
            </p>
            <a href="{{ route('candidate.profile.index') }}" class="btn btn-sm fw-semibold w-100"
               style="background:{{ $col }};color:#fff">
                <i class="bi bi-pencil-fill me-1"></i>Complete My Profile
            </a>
            @else
            <p class="text-success fw-semibold small mb-3">
                <i class="bi bi-check-circle-fill me-1"></i>Your profile is 100% complete!
            </p>
            <a href="{{ route('candidate.cv.show') }}" target="_blank" class="btn btn-sm btn-success fw-semibold w-100">
                <i class="bi bi-file-earmark-pdf-fill me-1"></i>View / Download CV
            </a>
            @endif
        </div>
    </div>
</div>

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">
    @php
        $statCards = [
            ['icon'=>'send-check-fill',      'value'=>$stats['total']       ?? 0, 'label'=>'Total Applications', 'bg'=>'#0D47A1', 'color'=>'#fff'],
            ['icon'=>'hourglass-split', 'value'=>$stats['pending']     ?? 0, 'label'=>'Pending Review',     'bg'=>'#FFB300', 'color'=>'#111'],
            ['icon'=>'star-fill',            'value'=>$stats['shortlisted'] ?? 0, 'label'=>'Shortlisted',        'bg'=>'#0288D1', 'color'=>'#fff'],
            ['icon'=>'calendar-check-fill',  'value'=>$stats['interview']   ?? 0, 'label'=>'Interview Stage',    'bg'=>'#2E7D32', 'color'=>'#fff'],
        ];
    @endphp
    @foreach($statCards as $card)
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 rounded-4 h-100" style="background:{{ $card['bg'] }};color:{{ $card['color'] }}">
            <div class="card-body d-flex align-items-center gap-3 p-3">
                <div class="rounded-3 p-2" style="background:rgba(255,255,255,.15)">
                    <i class="bi bi-{{ $card['icon'] }} fs-2"></i>
                </div>
                <div>
                    <div class="fw-bold" style="font-size:1.8rem;line-height:1">{{ $card['value'] }}</div>
                    <div class="small" style="opacity:.8">{{ $card['label'] }}</div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- QUICK ACTIONS --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <h6 class="fw-bold text-muted text-uppercase small mb-2">Quick Actions</h6>
    </div>
    <div class="col-md-3 col-sm-6">
        <a href="{{ route('careers.index') }}" class="card border-0 shadow-sm rounded-4 p-3 text-decoration-none d-flex flex-row align-items-center gap-3 h-100 hover-lift">
            <div class="rounded-3 p-2" style="background:#E8F0FE">
                <i class="bi bi-briefcase-fill fs-3" style="color:var(--kk-blue,#0D47A1)"></i>
            </div>
            <div>
                <div class="fw-semibold text-dark small">Browse Jobs</div>
                <div class="text-muted" style="font-size:.75rem">Find open vacancies</div>
            </div>
        </a>
    </div>
    <div class="col-md-3 col-sm-6">
        <a href="{{ route('candidate.profile.index') }}" class="card border-0 shadow-sm rounded-4 p-3 text-decoration-none d-flex flex-row align-items-center gap-3 h-100 hover-lift">
            <div class="rounded-3 p-2" style="background:#FFF8E1">
                <i class="bi bi-person-badge-fill fs-3" style="color:#F9A825"></i>
            </div>
            <div>
                <div class="fw-semibold text-dark small">Edit Profile</div>
                <div class="text-muted" style="font-size:.75rem">Personal info &amp; CV data</div>
            </div>
        </a>
    </div>
    <div class="col-md-3 col-sm-6">
        <a href="{{ route('candidate.documents.index') }}" class="card border-0 shadow-sm rounded-4 p-3 text-decoration-none d-flex flex-row align-items-center gap-3 h-100 hover-lift">
            <div class="rounded-3 p-2" style="background:#E8F5E9">
                <i class="bi bi-folder2 fs-3" style="color:#2E7D32"></i>
            </div>
            <div>
                <div class="fw-semibold text-dark small">My Documents</div>
                <div class="text-muted" style="font-size:.75rem">Upload certificates &amp; IDs</div>
            </div>
        </a>
    </div>
    <div class="col-md-3 col-sm-6">
        @if(isset($profile) && $profile->completeness_pct >= 100)
        <a href="{{ route('candidate.cv.show') }}" target="_blank" class="card border-0 shadow-sm rounded-4 p-3 text-decoration-none d-flex flex-row align-items-center gap-3 h-100 hover-lift">
            <div class="rounded-3 p-2" style="background:#FCE4EC">
                <i class="bi bi-file-earmark-pdf-fill fs-3" style="color:#C62828"></i>
            </div>
            <div>
                <div class="fw-semibold text-dark small">Download CV</div>
                <div class="text-muted" style="font-size:.75rem">Print-ready PDF format</div>
            </div>
        </a>
        @else
        <div class="card border-0 shadow-sm rounded-4 p-3 d-flex flex-row align-items-center gap-3 h-100" style="opacity:.6;cursor:not-allowed">
            <div class="rounded-3 p-2" style="background:#FAFAFA">
                <i class="bi bi-file-earmark-pdf-fill fs-3 text-muted"></i>
            </div>
            <div>
                <div class="fw-semibold text-muted small">Download CV</div>
                <div class="text-muted" style="font-size:.75rem">Complete profile first</div>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- RECENT APPLICATIONS --}}
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Recent Applications</h5>
        <a href="{{ route('candidate.applications.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3">Job Title</th>
                        <th>Employer</th>
                        <th>Status</th>
                        <th>Applied</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentApplications as $app)
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
                        $colour = $statusColours[$app->status] ?? 'secondary';
                        $label  = $statusLabels[$app->status]  ?? ucfirst($app->status);
                    @endphp
                    <tr>
                        <td class="px-4">
                            <div class="fw-semibold small">{{ $app->job->title ?? 'N/A' }}</div>
                            <div class="text-muted" style="font-size:.75rem">{{ $app->job->department ?? '' }}</div>
                        </td>
                        <td class="small text-muted">
                            @if(isset($app->job) && isset($app->job->job_type) && $app->job->job_type === 'client')
                                {{ $app->job->client_name ?? 'Client' }}
                            @else
                                Kikosi Kazi
                            @endif
                        </td>
                        <td><span class="badge bg-{{ $colour }} px-3 py-2 rounded-pill small">{{ $label }}</span></td>
                        <td class="text-muted small">{{ $app->applied_at ? \Carbon\Carbon::parse($app->applied_at)->format('d M Y') : $app->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('candidate.applications.show', $app) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye-fill me-1"></i>View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox-fill fs-2 d-block mb-2 opacity-50"></i>
                            <span class="small">No applications yet.</span><br>
                            <a href="{{ route('careers.index') }}" class="btn btn-sm btn-primary mt-2">
                                <i class="bi bi-briefcase-fill me-1"></i>Browse Open Jobs
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.hover-lift { transition: transform .15s ease, box-shadow .15s ease; }
.hover-lift:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.1) !important; }
</style>

@endsection
