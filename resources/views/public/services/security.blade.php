@extends('layouts.public')
@section('title', 'Security & Risk Management — Kikosi Kazi Security')
@section('meta_description', 'TSIA-registered, PSCGP-compliant security services in Tanzania — uniformed guards, mobile patrols, CCTV, access control, VIP protection and 24/7 alarm response from Kikosi Kazi Security. Request a free security assessment.')
@section('og_title', 'Security & Risk Management in Tanzania | Kikosi Kazi Security')
@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ request()->root() }}" },
        { "@@type": "ListItem", "position": 2, "name": "Services", "item": "{{ request()->root() }}/services" },
        { "@@type": "ListItem", "position": 3, "name": "Security & Risk Management", "item": "{{ request()->url() }}" }
    ]
}
</script>
@endpush
@section('navbar-class', 'solid')

@section('content')

{{-- HERO --}}
<section class="py-5" style="background:linear-gradient(135deg,#0D47A1 0%,#1565C0 100%);min-height:280px;display:flex;align-items:center">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('services.index') }}" class="text-white-50 text-decoration-none">Services</a></li>
                <li class="breadcrumb-item active text-white">Security &amp; Risk Management</li>
            </ol>
        </nav>
        <div class="d-flex align-items-center gap-3 mb-3">
            <div class="icon-circle" style="background:rgba(255,255,255,0.15);color:#fff;width:64px;height:64px;font-size:1.8rem">
                <i class="bi bi-shield-fill-check"></i>
            </div>
            <h1 class="display-5 fw-bold text-white mb-0">Security &amp; Risk Management</h1>
        </div>
        <p class="lead text-white-50">TSIA-registered &amp; PSCGP-compliant. Professionally trained, 24/7 protection for your business.</p>
    </div>
</section>

{{-- INTRO --}}
<section class="section-py bg-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title mb-4">Professional Security <span>You Can Trust</span></h2>
                <p class="text-muted fs-5">
                    The Security &amp; Risk Management division of Kikosi Kazi Security is built on a foundation of rigorous vetting, continuous training and strict operational
                    standards. All our guards are registered with the Tanzania Security Industry Association (TSIA) and fully compliant
                    with the Private Security Companies Governance Portal (PSCGP) under the Ministry of Home Affairs. They undergo
                    comprehensive background checks, physical fitness assessments and skills training before deployment. We serve
                    offices, factories, residential estates, retail outlets, construction sites, banks, NGOs and government institutions
                    across Tanzania — delivering professional protection 24 hours a day, every day of the year.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- SERVICES GRID --}}
<section class="section-py bg-light-blue">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">What We <span>Offer</span></h2>
            <p class="text-muted">Comprehensive security solutions for every threat environment.</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="card card-service h-100 p-4">
                    <div class="card-body">
                        <div class="icon-circle mb-3">
                            <i class="bi bi-shield-fill-check"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Uniformed Security Guards</h5>
                        <p class="text-muted small mb-0">
                            Professionally trained, licensed, uniformed guards for offices, factories, malls, residential estates
                            and institutions. All guards are TSIA-registered, PSCGP-compliant and equipped with communication devices and incident reporting systems.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card card-service h-100 p-4">
                    <div class="card-body">
                        <div class="icon-circle mb-3">
                            <i class="bi bi-camera-video-fill"></i>
                        </div>
                        <h5 class="fw-bold mb-2">CCTV Monitoring</h5>
                        <p class="text-muted small mb-0">
                            24/7 monitoring of your CCTV feeds with incident reporting and alert escalation. Our monitoring
                            centre operates around the clock with trained operators who respond immediately to any suspicious
                            activity detected on your cameras.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card card-service h-100 p-4">
                    <div class="card-body">
                        <div class="icon-circle mb-3">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>
                        <h5 class="fw-bold mb-2">VIP &amp; Executive Protection</h5>
                        <p class="text-muted small mb-0">
                            Discreet, highly trained close protection officers for executives and dignitaries. Our VIP protection
                            specialists are experienced in threat assessment, advance work, secure route planning and emergency
                            extraction procedures.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card card-service h-100 p-4">
                    <div class="card-body">
                        <div class="icon-circle mb-3">
                            <i class="bi bi-car-front-fill"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Mobile Patrols</h5>
                        <p class="text-muted small mb-0">
                            Regular scheduled and random patrols across your premises and perimeter. Our patrol vehicles and
                            officers conduct systematic checks, deter criminal activity and provide rapid response to any
                            incidents or alarms triggered at your site.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card card-service h-100 p-4">
                    <div class="card-body">
                        <div class="icon-circle mb-3">
                            <i class="bi bi-calendar-event-fill"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Event Security</h5>
                        <p class="text-muted small mb-0">
                            Crowd management, access control and emergency response for corporate and public events. From
                            board meetings and product launches to concerts and sporting events, our event security teams
                            ensure a safe experience for all attendees.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 h-100 p-4 rounded-4 d-flex align-items-center justify-content-center text-center" style="background:var(--kk-blue)">
                    <div>
                        <i class="bi bi-headset display-4 mb-3 text-white"></i>
                        <h5 class="fw-bold text-white mb-2">24/7 Support</h5>
                        <p class="text-white-50 small mb-3">Round-the-clock emergency hotline and incident management for all contracted clients.</p>
                        <a href="{{ route('contact.index') }}" class="btn btn-amber btn-sm fw-semibold px-4">Contact Us</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-5" style="background:#0a2342">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-8 text-white">
                <h3 class="fw-bold mb-2">Request a Free Security Assessment</h3>
                <p class="text-white-50 mb-0">Our security consultants will visit your site, assess your vulnerabilities and recommend a tailored protection plan at no cost to you.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('contact.index') }}" class="btn btn-amber btn-lg px-5 fw-semibold">
                    <i class="bi bi-calendar-check-fill me-2"></i>Book Assessment
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
