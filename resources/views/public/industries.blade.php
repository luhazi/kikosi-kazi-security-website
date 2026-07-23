@extends('layouts.public')
@section('title', 'Industries We Serve — Kikosi Kazi Security')
@section('meta_description', 'Kikosi Kazi Security serves banks, embassies, NGOs, hospitals, factories, hotels, airports, construction firms and government institutions across Tanzania with sector-specialist security and business support services.')
@section('og_title', 'Industries We Serve | Kikosi Kazi Security, Tanzania')
@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ request()->root() }}" },
        { "@@type": "ListItem", "position": 2, "name": "Industries", "item": "{{ request()->url() }}" }
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
                <li class="breadcrumb-item active text-white">Industries</li>
            </ol>
        </nav>
        <h1 class="display-5 fw-bold text-white mb-2">Industries We Serve</h1>
        <p class="lead text-white-50">Sector expertise across Tanzania's most dynamic industries.</p>
    </div>
</section>

{{-- INTRO --}}
<section class="py-5 bg-white">
    <div class="container">
        <div class="col-lg-8 mx-auto text-center">
            <h2 class="section-title mb-3">Serving Every <span>Sector</span></h2>
            <p class="text-muted">
                From international hospitality groups and commercial banks to construction firms and NGOs, Kikosi Kazi has
                the experience and capacity to serve organisations of every type across Tanzania. Our sector specialists
                understand your industry's unique requirements and tailor our services accordingly.
            </p>
        </div>
    </div>
</section>

{{-- INDUSTRIES GRID --}}
<section class="section-py bg-light-blue">
    <div class="container">
        <div class="row g-4">
            {{-- Hospitality --}}
            <div class="col-lg-4 col-md-6">
                <div class="card card-service h-100 p-4">
                    <div class="card-body">
                        <div class="icon-circle mb-3"><i class="bi bi-building-fill"></i></div>
                        <h5 class="fw-bold mb-2">Hospitality &amp; Hotels</h5>
                        <p class="text-muted small mb-0">
                            We provide discreet uniformed security, housekeeping support, guest-facing cleaning services and
                            HR recruitment for hotels, resorts and lodges. Our teams are trained in hospitality standards to
                            blend seamlessly with your brand identity and guest experience.
                        </p>
                    </div>
                </div>
            </div>
            {{-- Banking --}}
            <div class="col-lg-4 col-md-6">
                <div class="card card-service h-100 p-4">
                    <div class="card-body">
                        <div class="icon-circle mb-3"><i class="bi bi-bank"></i></div>
                        <h5 class="fw-bold mb-2">Banking &amp; Finance</h5>
                        <p class="text-muted small mb-0">
                            High-security armed and unarmed guards, cash-in-transit support, CCTV monitoring and risk assessment
                            for banks, microfinance institutions and financial services companies. Fully compliant with Bank
                            of Tanzania security requirements.
                        </p>
                    </div>
                </div>
            </div>
            {{-- Construction --}}
            <div class="col-lg-4 col-md-6">
                <div class="card card-service h-100 p-4">
                    <div class="card-body">
                        <div class="icon-circle mb-3"><i class="bi bi-hammer"></i></div>
                        <h5 class="fw-bold mb-2">Construction</h5>
                        <p class="text-muted small mb-0">
                            Site security, mobile patrols, labour supply, WCF insurance and post-construction cleaning for
                            construction companies, contractors and property developers. We protect your assets through every
                            phase of the project lifecycle.
                        </p>
                    </div>
                </div>
            </div>
            {{-- NGOs --}}
            <div class="col-lg-4 col-md-6">
                <div class="card card-service h-100 p-4">
                    <div class="card-body">
                        <div class="icon-circle mb-3"><i class="bi bi-globe-europe-africa"></i></div>
                        <h5 class="fw-bold mb-2">NGOs &amp; INGOs</h5>
                        <p class="text-muted small mb-0">
                            Specialist security, HR compliance and office cleaning for international and local NGOs operating
                            in Tanzania. We understand the unique operational contexts, donor requirements and duty-of-care
                            obligations facing development sector organisations.
                        </p>
                    </div>
                </div>
            </div>
            {{-- Retail --}}
            <div class="col-lg-4 col-md-6">
                <div class="card card-service h-100 p-4">
                    <div class="card-body">
                        <div class="icon-circle mb-3"><i class="bi bi-cart4"></i></div>
                        <h5 class="fw-bold mb-2">Retail &amp; Supermarkets</h5>
                        <p class="text-muted small mb-0">
                            Loss prevention officers, entrance security, cleaning services and HR support for retail chains,
                            supermarkets and shopping malls. We help protect your merchandise, staff and customers while
                            maintaining a welcoming and clean shopping environment.
                        </p>
                    </div>
                </div>
            </div>
            {{-- Government --}}
            <div class="col-lg-4 col-md-6">
                <div class="card card-service h-100 p-4">
                    <div class="card-body">
                        <div class="icon-circle mb-3"><i class="bi bi-flag-fill"></i></div>
                        <h5 class="fw-bold mb-2">Government &amp; Parastatals</h5>
                        <p class="text-muted small mb-0">
                            Tendered security and cleaning contracts for government ministries, departments, agencies and
                            state-owned enterprises. We meet all public procurement compliance requirements and maintain
                            the highest professional standards in government service delivery.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-5" style="background:var(--kk-blue)">
    <div class="container text-center">
        <h2 class="fw-bold text-white mb-3">Don't See Your Industry?</h2>
        <p class="text-white-50 mb-4">We serve many more sectors across Tanzania. Get in touch to discuss your specific requirements.</p>
        <a href="{{ route('contact.index') }}" class="btn btn-amber btn-lg px-5 fw-semibold">
            <i class="bi bi-chat-dots-fill me-2"></i>Speak to a Specialist
        </a>
    </div>
</section>

@endsection
