@extends('layouts.public')
@section('title', 'Insurance Advisory & Brokerage — Kikosi Kazi Security')
@section('meta_description', 'Insurance Advisory & Brokerage from Kikosi Kazi Security — group life, WCF, motor, property and specialised covers arranged with leading Tanzanian and international insurers, with a partner who advocates for you at claim time.')
@section('og_title', 'Insurance Advisory & Brokerage in Tanzania | Kikosi Kazi Security')
@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ request()->root() }}" },
        { "@@type": "ListItem", "position": 2, "name": "Services", "item": "{{ request()->root() }}/services" },
        { "@@type": "ListItem", "position": 3, "name": "Insurance Advisory & Brokerage", "item": "{{ request()->url() }}" }
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
                <li class="breadcrumb-item active text-white">Insurance Advisory &amp; Brokerage</li>
            </ol>
        </nav>
        <div class="d-flex align-items-center gap-3 mb-3">
            <div class="icon-circle" style="background:rgba(255,255,255,0.15);color:#fff;width:64px;height:64px;font-size:1.8rem">
                <i class="bi bi-umbrella-fill"></i>
            </div>
            <h1 class="display-5 fw-bold text-white mb-0">Insurance Advisory &amp; Brokerage</h1>
        </div>
        <p class="lead text-white-50">Protecting your business, your employees and your assets with tailored insurance placement.</p>
    </div>
</section>

{{-- INTRO --}}
<section class="section-py bg-white">
    <div class="container">
        <div class="col-lg-8 mx-auto text-center">
            <h2 class="section-title mb-4">Protect What <span>Matters Most</span></h2>
            <p class="text-muted fs-5">
                Risk is an unavoidable part of doing business. Our Insurance Advisory &amp; Brokerage division works with leading Tanzanian
                and international insurers to arrange comprehensive, cost-effective cover that safeguards your organisation, your
                employees and your assets. We handle the complexity of the insurance market so you can focus on running your business.
            </p>
        </div>
    </div>
</section>

{{-- SERVICES GRID --}}
<section class="section-py bg-light-blue">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Our Insurance <span>Products</span></h2>
            <p class="text-muted mx-auto" style="max-width:720px">
                From statutory compliance to protecting your business assets and your family, our cover is arranged
                through licensed Tanzanian insurers and regulated under the Tanzania Insurance Regulatory Authority (TIRA).
            </p>
        </div>

        {{-- BLOCK 1: CORE ADVISORY & COMPLIANCE --}}
        <div class="mb-3">
            <h3 class="fw-bold mb-1" style="color:var(--kk-blue)">
                <i class="bi bi-clipboard2-check-fill me-2"></i>Core Advisory &amp; Compliance
            </h3>
            <p class="text-muted mb-4">Expert guidance and statutory cover that keep your organisation protected and fully compliant.</p>
        </div>
        <div class="row g-4 mb-5">
            <div class="col-lg-4 col-md-6">
                <div class="card card-service h-100 p-4">
                    <div class="card-body">
                        <div class="icon-circle mb-3"><i class="bi bi-clipboard2-pulse-fill"></i></div>
                        <h5 class="fw-bold mb-2">Risk Assessment</h5>
                        <p class="text-muted small mb-0">
                            Thorough on-site and document review to identify, quantify and prioritise the risks facing your business.
                            Our consultants produce a detailed report with practical mitigation recommendations and an optimal
                            insurance strategy aligned to your budget and risk appetite.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card card-service h-100 p-4">
                    <div class="card-body">
                        <div class="icon-circle mb-3"><i class="bi bi-shield-fill-plus"></i></div>
                        <h5 class="fw-bold mb-2">WCF Cover</h5>
                        <p class="text-muted small mb-0">
                            Workers' Compensation Fund (WCF) compliance cover under the Workers' Compensation Act, No. 20 of 2008 —
                            protecting your employees against workplace injury and occupational disease while keeping your
                            organisation fully compliant with Tanzanian law.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card card-service h-100 p-4">
                    <div class="card-body">
                        <div class="icon-circle mb-3"><i class="bi bi-file-earmark-check-fill"></i></div>
                        <h5 class="fw-bold mb-2">Claims Processing Support</h5>
                        <p class="text-muted small mb-0">
                            When the unexpected happens, our claims team guides you through every step — from loss notification and
                            evidence gathering to insurer liaison and final settlement. We fight your corner to secure fair and
                            prompt payment of valid claims.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- BLOCK 2: BUSINESS INSURANCE SOLUTIONS --}}
        <div class="mb-3">
            <h3 class="fw-bold mb-1" style="color:var(--kk-blue)">
                <i class="bi bi-briefcase-fill me-2"></i>Business Insurance Solutions
            </h3>
            <p class="text-muted mb-4">Comprehensive protection for your fleet, premises, assets and contractual obligations.</p>
        </div>
        <div class="row g-4 mb-5">
            <div class="col-lg-4 col-md-6">
                <div class="card card-service h-100 p-4">
                    <div class="card-body">
                        <div class="icon-circle mb-3"><i class="bi bi-truck"></i></div>
                        <h5 class="fw-bold mb-2">Motor Insurance (Fleet)</h5>
                        <p class="text-muted small mb-0">
                            Comprehensive and Third-Party cover for corporate fleets — from a single company car to a nationwide
                            fleet of trucks and plant. We secure competitive premiums and streamlined claims to keep your vehicles
                            and drivers moving safely across Tanzania.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card card-service h-100 p-4">
                    <div class="card-body">
                        <div class="icon-circle mb-3"><i class="bi bi-fire"></i></div>
                        <h5 class="fw-bold mb-2">Fire &amp; All Risks Insurance</h5>
                        <p class="text-muted small mb-0">
                            Protect your buildings, inventory, machinery and assets against fire, lightning, explosion and a wide
                            range of unforeseen perils. Ideal for offices, warehouses, factories and retail premises, with sums
                            insured structured around the true replacement value of your assets.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card card-service h-100 p-4">
                    <div class="card-body">
                        <div class="icon-circle mb-3"><i class="bi bi-safe2-fill"></i></div>
                        <h5 class="fw-bold mb-2">Theft &amp; Burglary Insurance</h5>
                        <p class="text-muted small mb-0">
                            Cover against loss or damage from break-ins, forced entry and theft — including your fixtures and
                            fittings. Safeguard your equipment, stock and office contents so a single incident never threatens
                            the continuity of your operations.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card card-service h-100 p-4">
                    <div class="card-body">
                        <div class="icon-circle mb-3"><i class="bi bi-file-earmark-text-fill"></i></div>
                        <h5 class="fw-bold mb-2">Bonds Insurance</h5>
                        <p class="text-muted small mb-0">
                            Tender bonds, performance bonds and security (advance payment) bonds that let you bid and deliver on
                            contracts with confidence. We arrange the guarantees required by clients and procuring entities so your
                            business meets every tender and contractual obligation.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card card-service h-100 p-4">
                    <div class="card-body">
                        <div class="icon-circle mb-3"><i class="bi bi-heart-pulse-fill"></i></div>
                        <h5 class="fw-bold mb-2">Group Life &amp; Employee Benefits</h5>
                        <p class="text-muted small mb-0">
                            Competitive group life, critical illness and disability cover for your entire workforce. We negotiate
                            favourable terms with leading insurers and manage the scheme end-to-end — from enrolment and premium
                            collection to claims settlement and renewal.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 h-100 p-4 rounded-4 d-flex align-items-center justify-content-center text-center" style="background:var(--kk-blue)">
                    <div>
                        <i class="bi bi-shield-lock-fill display-5 mb-3 text-white"></i>
                        <h5 class="fw-bold text-white mb-2">Bespoke Business Covers</h5>
                        <p class="text-white-50 small mb-3">Marine, public liability, professional indemnity or a unique risk profile? We arrange tailor-made solutions.</p>
                        <a href="{{ route('contact.index') }}" class="btn btn-amber btn-sm fw-semibold px-4">Enquire Now</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- BLOCK 3: PERSONAL INSURANCE SOLUTIONS --}}
        <div class="mb-3">
            <h3 class="fw-bold mb-1" style="color:var(--kk-blue)">
                <i class="bi bi-person-check-fill me-2"></i>Personal Insurance Solutions
            </h3>
            <p class="text-muted mb-4">Everyday protection for you, your family, your home and your journeys.</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="card card-service h-100 p-4">
                    <div class="card-body">
                        <div class="icon-circle mb-3"><i class="bi bi-house-heart-fill"></i></div>
                        <h5 class="fw-bold mb-2">Domestic Package Insurance</h5>
                        <p class="text-muted small mb-0">
                            All-in-one protection for your home, household goods and personal liabilities under a single convenient
                            policy — covering your building, furniture, electronics and valuables against fire, theft and
                            accidental loss.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card card-service h-100 p-4">
                    <div class="card-body">
                        <div class="icon-circle mb-3"><i class="bi bi-car-front-fill"></i></div>
                        <h5 class="fw-bold mb-2">Personal Motor Insurance</h5>
                        <p class="text-muted small mb-0">
                            Comprehensive and Third-Party options for private vehicles, with fair premiums and fast claims. Drive
                            with peace of mind knowing that you, your car and other road users are properly protected on every
                            journey.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card card-service h-100 p-4">
                    <div class="card-body">
                        <div class="icon-circle mb-3"><i class="bi bi-person-arms-up"></i></div>
                        <h5 class="fw-bold mb-2">Personal Accident Insurance</h5>
                        <p class="text-muted small mb-0">
                            Financial security for you and your family against accidental injury, permanent disability or accidental
                            death — providing lump-sum benefits and medical expense cover exactly when it matters most.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card card-service h-100 p-4">
                    <div class="card-body">
                        <div class="icon-circle mb-3"><i class="bi bi-airplane-fill"></i></div>
                        <h5 class="fw-bold mb-2">Travel Insurance</h5>
                        <p class="text-muted small mb-0">
                            Cover for medical emergencies, trip cancellation, delays and lost baggage on both international and local
                            journeys — so you can travel for business or leisure with complete confidence.
                        </p>
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
                <h3 class="fw-bold mb-2">Get a Free Risk Assessment Today</h3>
                <p class="text-white-50 mb-0">Our insurance consultants will assess your risk exposure at no cost and recommend the cover your business truly needs.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('contact.index') }}" class="btn btn-amber btn-lg px-5 fw-semibold">
                    <i class="bi bi-shield-fill-check me-2"></i>Free Assessment
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
