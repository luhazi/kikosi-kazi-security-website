@extends('layouts.public')
@section('title', 'About Us — Kikosi Kazi Security')
@section('meta_description', 'Founded in Dar es Salaam, Kikosi Kazi Security has grown from a specialist security provider into Tanzania\'s trusted integrated business support partner — serving 200+ clients across four specialist divisions.')
@section('og_title', 'About Kikosi Kazi Security — Tanzania\'s Trusted Integrated Services Partner')
@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ request()->root() }}" },
        { "@@type": "ListItem", "position": 2, "name": "About Us", "item": "{{ request()->url() }}" }
    ]
}
</script>
@endpush
@section('navbar-class', 'solid')

@section('content')
@php
    $sec = $section ?? 'all';
    $heroData = [
        'story'   => ['Our Story', "How Kikosi Kazi grew into Tanzania's trusted integrated services partner."],
        'mission' => ['Mission, Vision & Core Values', 'What drives us — and the principles that guide everything we do.'],
        'partner' => ['Why Partner With Us', 'The advantages of choosing Kikosi Kazi as your services partner.'],
    ];
    [$heroTitle, $heroSub] = $heroData[$sec] ?? ['About Kikosi Kazi Security', 'Your Trusted Partner for Security, HR, Insurance & Cleaning Services'];
    $heroCrumb = $heroData[$sec][0] ?? 'About Us';
@endphp

{{-- HERO --}}
<section class="py-5" style="background:linear-gradient(135deg,#0D47A1 0%,#1565C0 100%);min-height:280px;display:flex;align-items:center">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Home</a></li>
                @if($sec !== 'all')
                <li class="breadcrumb-item"><a href="{{ route('about') }}" class="text-white-50 text-decoration-none">About Us</a></li>
                @endif
                <li class="breadcrumb-item active text-white">{{ $heroCrumb }}</li>
            </ol>
        </nav>
        <h1 class="display-5 fw-bold text-white mb-2">{{ $heroTitle }}</h1>
        <p class="lead text-white-50">{{ $heroSub }}</p>
    </div>
</section>

{{-- OUR STORY --}}
@if($sec==='all' || $sec==='story')
<section class="section-py bg-white">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span id="our-story" style="display:block;scroll-margin-top:90px"></span>
                <h2 class="section-title mb-4">Our <span>Story</span></h2>
                <p class="text-muted mb-4">
                    Kikosi Kazi Security was established in Dar es Salaam, Tanzania, with a singular vision: to deliver world-class
                    integrated services that empower Tanzanian businesses to operate safely, efficiently and competitively. Starting as a
                    security company serving a handful of local clients, we quickly gained a reputation for professionalism, reliability and
                    accountability that set us apart in a crowded market.
                </p>
                <p class="text-muted">
                    Today, we proudly serve over 200 clients across Tanzania — from multinational corporations and international NGOs to
                    government agencies and growing SMEs. Our portfolio has expanded to encompass four specialist divisions: Security &amp;
                    Risk Management, Human Capital Solutions, Insurance Advisory &amp; Brokerage, and Facility Management Services. Each division is staffed by trained professionals
                    who bring deep sector expertise and an unwavering commitment to client satisfaction. Over more than a decade of
                    operations, Kikosi Kazi has become synonymous with trust, excellence and results.
                </p>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="rounded-4 p-4 text-center" style="background:#E8F0FE">
                            <div class="display-5 fw-bold" style="color:var(--kk-blue)">500+</div>
                            <div class="small text-muted">Guards Deployed</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="rounded-4 p-4 text-center" style="background:#FFF8E1">
                            <div class="display-5 fw-bold" style="color:var(--kk-amber)">200+</div>
                            <div class="small text-muted">Clients Served</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="rounded-4 p-4 text-center" style="background:#FFF8E1">
                            <div class="display-5 fw-bold" style="color:var(--kk-amber)">10+</div>
                            <div class="small text-muted">Years Experience</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="rounded-4 p-4 text-center" style="background:#E8F0FE">
                            <div class="display-5 fw-bold" style="color:var(--kk-blue)">4</div>
                            <div class="small text-muted">Service Divisions</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- MISSION & VISION --}}
@if($sec==='all' || $sec==='mission')
<section class="section-py bg-light-blue">
    <div class="container">
        <span id="mission-vision" style="display:block;scroll-margin-top:90px"></span>
        <div class="text-center mb-5">
            <h2 class="section-title">Mission &amp; <span>Vision</span></h2>
        </div>
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4" style="border-left:4px solid var(--kk-blue) !important">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="icon-circle">
                                <i class="bi bi-bullseye"></i>
                            </div>
                            <h4 class="fw-bold mb-0" style="color:var(--kk-blue)">Our Mission</h4>
                        </div>
                        <p class="text-muted mb-0 fs-5">
                            "To provide world-class security, HR, insurance and cleaning services that enable Tanzanian businesses
                            to operate safely and efficiently, while creating sustainable employment opportunities for Tanzanians."
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4" style="border-left:4px solid var(--kk-amber) !important">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="icon-circle" style="background:rgba(255,179,0,0.1);color:var(--kk-amber)">
                                <i class="bi bi-eye-fill"></i>
                            </div>
                            <h4 class="fw-bold mb-0" style="color:var(--kk-blue)">Our Vision</h4>
                        </div>
                        <p class="text-muted mb-0 fs-5">
                            "To be East Africa's most trusted integrated services company by 2030 — recognised for our
                            professionalism, innovation and positive impact on communities and businesses across the region."
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- OUR VALUES --}}
@if($sec==='all' || $sec==='mission')
<section class="section-py bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Our Core <span>Values</span></h2>
            <p class="text-muted">The principles that guide everything we do.</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="card card-service h-100 p-4 text-center">
                    <div class="icon-circle mx-auto mb-3">
                        <i class="bi bi-award-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Integrity</h5>
                    <p class="text-muted small mb-0">We act with honesty and transparency in all our dealings with clients, employees and stakeholders.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card card-service h-100 p-4 text-center">
                    <div class="icon-circle mx-auto mb-3">
                        <i class="bi bi-briefcase-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Professionalism</h5>
                    <p class="text-muted small mb-0">Our team maintains the highest standards of conduct, appearance and service delivery at all times.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card card-service h-100 p-4 text-center">
                    <div class="icon-circle mx-auto mb-3">
                        <i class="bi bi-clock-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Reliability</h5>
                    <p class="text-muted small mb-0">You can count on us. We show up, we follow through and we deliver on our commitments — every time.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card card-service h-100 p-4 text-center">
                    <div class="icon-circle mx-auto mb-3">
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Excellence</h5>
                    <p class="text-muted small mb-0">We continuously improve our processes, invest in training and pursue best practice in every service we offer.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- WHY PARTNER WITH US --}}
@if($sec==='all' || $sec==='partner')
<section class="section-py bg-light-blue">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span id="why-partner" style="display:block;scroll-margin-top:90px"></span>
                <h2 class="section-title mb-4">Why <span>Partner With Us?</span></h2>
                <ul class="list-unstyled">
                    <li class="d-flex align-items-start gap-3 mb-3">
                        <i class="bi bi-check-circle-fill fs-5 mt-1" style="color:var(--kk-blue)"></i>
                        <span class="text-muted">TSIA-registered security personnel, fully compliant with the Private Security Companies Governance Portal (PSCGP) under the Ministry of Home Affairs, with rigorous background checks and ongoing training.</span>
                    </li>
                    <li class="d-flex align-items-start gap-3 mb-3">
                        <i class="bi bi-check-circle-fill fs-5 mt-1" style="color:var(--kk-blue)"></i>
                        <span class="text-muted">Dedicated account managers who understand your business and provide personalised service.</span>
                    </li>
                    <li class="d-flex align-items-start gap-3 mb-3">
                        <i class="bi bi-check-circle-fill fs-5 mt-1" style="color:var(--kk-blue)"></i>
                        <span class="text-muted">Integrated multi-service capability — one trusted partner for security, HR, insurance and cleaning.</span>
                    </li>
                    <li class="d-flex align-items-start gap-3 mb-3">
                        <i class="bi bi-check-circle-fill fs-5 mt-1" style="color:var(--kk-blue)"></i>
                        <span class="text-muted">Pan-Tanzania operations with local knowledge in every region we serve.</span>
                    </li>
                    <li class="d-flex align-items-start gap-3 mb-3">
                        <i class="bi bi-check-circle-fill fs-5 mt-1" style="color:var(--kk-blue)"></i>
                        <span class="text-muted">Competitive pricing, transparent contracts and no hidden fees.</span>
                    </li>
                    <li class="d-flex align-items-start gap-3">
                        <i class="bi bi-check-circle-fill fs-5 mt-1" style="color:var(--kk-blue)"></i>
                        <span class="text-muted">24/7 emergency response hotline staffed by trained incident managers.</span>
                    </li>
                </ul>
                <a href="{{ route('contact.index') }}" class="btn btn-kk btn-lg px-5 mt-3">Get In Touch</a>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow rounded-4 p-5 text-center" style="background:var(--kk-blue)">
                    <i class="bi bi-hand-thumbs-up-fill display-1 mb-4" style="color:var(--kk-amber)"></i>
                    <h3 class="fw-bold text-white mb-3">10+ Years of Excellence</h3>
                    <p class="text-white-50">
                        Since our founding, Kikosi Kazi has grown from a small security outfit to a full-service corporate
                        services provider trusted by hundreds of businesses across Tanzania.
                    </p>
                    <a href="{{ route('careers.index') }}" class="btn btn-amber mt-3 fw-semibold px-4">Join Our Team</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- CEO MESSAGE --}}
@if($ceo)
@if($sec==='all')
<section class="section-py bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Message from the <span>CEO</span></h2>
        </div>
        <div class="row align-items-center g-5">
            <div class="col-lg-4 text-center">
                @if($ceo->photo_path)
                <img src="{{ asset('storage/' . $ceo->photo_path) }}" alt="{{ $ceo->name }}"
                     class="rounded-circle shadow-lg mb-4"
                     style="width:200px;height:200px;object-fit:cover;border:6px solid var(--kk-blue)">
                @else
                <div class="rounded-circle shadow-lg mx-auto mb-4 d-flex align-items-center justify-content-center"
                     style="width:200px;height:200px;background:var(--kk-blue);border:6px solid var(--kk-amber)">
                    <i class="bi bi-person-fill text-white" style="font-size:5rem"></i>
                </div>
                @endif
                <h4 class="fw-bold mb-1" style="color:var(--kk-blue)">{{ $ceo->name }}</h4>
                <p class="text-muted mb-0">{{ $ceo->role }}</p>
                @if($ceo->linkedin)
                <a href="{{ $ceo->linkedin }}" target="_blank" rel="noopener noreferrer"
                   class="btn btn-sm btn-outline-primary mt-3">
                    <i class="bi bi-linkedin me-1"></i>LinkedIn
                </a>
                @endif
            </div>
            <div class="col-lg-8">
                <div class="position-relative ps-4" style="border-left:4px solid var(--kk-amber)">
                    <i class="bi bi-quote display-4 position-absolute" style="top:-10px;left:-10px;color:var(--kk-amber);opacity:0.2"></i>
                    <div class="text-muted fs-5 lh-lg" style="white-space:pre-line">{{ $ceo->ceo_message }}</div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
@endif

{{-- MEET THE TEAM --}}
@if($teamMembers->count() > 0)
@if($sec==='all')
<section class="section-py bg-light-blue">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Meet Our <span>Team</span></h2>
            <p class="text-muted">The dedicated professionals who drive our mission every day.</p>
        </div>
        <div class="row g-4">
            @foreach($teamMembers as $member)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 text-center p-4">
                    @if($member->photo_path)
                    <img src="{{ asset('storage/' . $member->photo_path) }}" alt="{{ $member->name }}"
                         class="rounded-circle mx-auto mb-3 shadow-sm"
                         style="width:110px;height:110px;object-fit:cover;border:3px solid var(--kk-blue)">
                    @else
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center shadow-sm"
                         style="width:110px;height:110px;background:var(--kk-blue);border:3px solid var(--kk-amber)">
                        <i class="bi bi-person-fill text-white" style="font-size:2.5rem"></i>
                    </div>
                    @endif

                    @if($member->is_ceo)
                    <span class="badge rounded-pill mb-2" style="background:var(--kk-amber);color:#000">
                        <i class="bi bi-star-fill me-1"></i>CEO
                    </span>
                    @endif

                    <h6 class="fw-bold mb-1">{{ $member->name }}</h6>
                    <p class="small fw-semibold mb-1" style="color:var(--kk-blue)">{{ $member->role }}</p>
                    @if($member->department)
                    <p class="small text-muted mb-2">{{ $member->department }}</p>
                    @endif
                    @if($member->bio)
                    <p class="small text-muted mb-3" style="font-size:.8rem;line-height:1.5">
                        {{ Str::limit($member->bio, 100) }}
                    </p>
                    @endif

                    <div class="d-flex justify-content-center gap-2 mt-auto pt-2">
                        @if($member->email)
                        <a href="mailto:{{ $member->email }}" class="btn btn-sm btn-outline-secondary rounded-circle p-1"
                           style="width:32px;height:32px;line-height:1.2" title="Email">
                            <i class="bi bi-envelope-fill" style="font-size:.75rem"></i>
                        </a>
                        @endif
                        @if($member->linkedin)
                        <a href="{{ $member->linkedin }}" target="_blank" rel="noopener noreferrer"
                           class="btn btn-sm btn-outline-primary rounded-circle p-1"
                           style="width:32px;height:32px;line-height:1.2" title="LinkedIn">
                            <i class="bi bi-linkedin" style="font-size:.75rem"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endif

@endsection
