@extends('layouts.public')
@section('title', 'Human Capital Solutions — Kikosi Kazi Security')
@section('meta_description', 'Human Capital Solutions from Kikosi Kazi Security — recruitment, HR outsourcing, payroll management, training and Tanzanian labour-law compliance that help your organisation attract, develop and retain great people.')
@section('og_title', 'Human Capital Solutions — Recruitment, Payroll & HR in Tanzania | Kikosi Kazi Security')
@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ request()->root() }}" },
        { "@@type": "ListItem", "position": 2, "name": "Services", "item": "{{ request()->root() }}/services" },
        { "@@type": "ListItem", "position": 3, "name": "Human Capital Solutions", "item": "{{ request()->url() }}" }
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
                <li class="breadcrumb-item active text-white">Human Capital Solutions</li>
            </ol>
        </nav>
        <div class="d-flex align-items-center gap-3 mb-3">
            <div class="icon-circle" style="background:rgba(255,255,255,0.15);color:#fff;width:64px;height:64px;font-size:1.8rem">
                <i class="bi bi-people-fill"></i>
            </div>
            <h1 class="display-5 fw-bold text-white mb-0">Human Capital Solutions</h1>
        </div>
        <p class="lead text-white-50">Strategic human resource solutions that help Tanzanian businesses attract, develop and retain great people.</p>
    </div>
</section>

{{-- INTRO --}}
<section class="section-py bg-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title mb-4">Your People Are Your <span>Greatest Asset</span></h2>
                <p class="text-muted fs-5">
                    Our Human Capital Solutions division partners with organisations of all sizes to build high-performing teams, ensure full
                    compliance with Tanzanian labour law and create workplaces where employees thrive. Whether you need to recruit a
                    single executive or restructure an entire department, we bring the expertise, tools and networks to get it done right.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- SERVICES GRID --}}
<section class="section-py bg-light-blue">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Our HR <span>Services</span></h2>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="card card-service h-100 p-4">
                    <div class="card-body">
                        <div class="icon-circle mb-3"><i class="bi bi-search"></i></div>
                        <h5 class="fw-bold mb-2">Talent Acquisition</h5>
                        <p class="text-muted small mb-0">
                            End-to-end recruitment for permanent, contract and temporary roles across all industries. We source, screen,
                            interview and shortlist candidates so you see only the best. Our database of pre-vetted candidates spans
                            security, finance, administration, operations and management disciplines.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card card-service h-100 p-4">
                    <div class="card-body">
                        <div class="icon-circle mb-3"><i class="bi bi-mortarboard-fill"></i></div>
                        <h5 class="fw-bold mb-2">Staff Training &amp; Development</h5>
                        <p class="text-muted small mb-0">
                            Bespoke training programmes covering leadership, customer service, communication, supervisory skills and
                            technical competencies. Our facilitators are experienced practitioners who deliver practical, results-focused
                            learning in both classroom and on-site settings.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card card-service h-100 p-4">
                    <div class="card-body">
                        <div class="icon-circle mb-3"><i class="bi bi-clipboard-check-fill"></i></div>
                        <h5 class="fw-bold mb-2">HR Audits &amp; Compliance</h5>
                        <p class="text-muted small mb-0">
                            Comprehensive review of your HR policies, employment contracts, records and practices against Tanzanian
                            labour legislation. We identify gaps, advise on corrective action and help you build a compliant, fair
                            workplace that protects both employer and employee.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card card-service h-100 p-4">
                    <div class="card-body">
                        <div class="icon-circle mb-3"><i class="bi bi-calculator-fill"></i></div>
                        <h5 class="fw-bold mb-2">Payroll Advisory</h5>
                        <p class="text-muted small mb-0">
                            Payroll management, PAYE calculations, NSSF and WCF contributions, statutory deductions and end-of-service
                            computations. We ensure your payroll is accurate, compliant and delivered on time — every month without fail.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card card-service h-100 p-4">
                    <div class="card-body">
                        <div class="icon-circle mb-3"><i class="bi bi-bank"></i></div>
                        <h5 class="fw-bold mb-2">Labour Law Consultancy</h5>
                        <p class="text-muted small mb-0">
                            Expert guidance on the Employment and Labour Relations Act, dispute resolution, disciplinary procedures,
                            retrenchment processes and representation before the Commission for Mediation and Arbitration (CMA).
                            We protect your business and treat employees fairly.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 h-100 p-4 rounded-4 d-flex align-items-center justify-content-center text-center" style="background:var(--kk-blue)">
                    <div>
                        <i class="bi bi-graph-up-arrow display-4 mb-3 text-white"></i>
                        <h5 class="fw-bold text-white mb-2">Performance Management</h5>
                        <p class="text-white-50 small mb-3">Design and implement KPI frameworks, appraisal systems and performance improvement plans.</p>
                        <a href="{{ route('contact.index') }}" class="btn btn-amber btn-sm fw-semibold px-4">Enquire Now</a>
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
                <h3 class="fw-bold mb-2">Ready to Build a Stronger Team?</h3>
                <p class="text-white-50 mb-0">Contact us today for a free HR consultation and discover how we can help your business grow through better people management.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('contact.index') }}" class="btn btn-amber btn-lg px-5 fw-semibold">
                    <i class="bi bi-chat-dots-fill me-2"></i>Contact Us Today
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
