@extends('layouts.public')
@section('title', 'Facility Management Services — Kikosi Kazi Security')
@section('meta_description', 'Facility Management Services from Kikosi Kazi Security — office, industrial and post-construction cleaning, deep sanitisation and waste management delivered by trained, uniformed crews across Tanzania.')
@section('og_title', 'Facility Management & Cleaning Services in Tanzania | Kikosi Kazi Security')
@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ request()->root() }}" },
        { "@@type": "ListItem", "position": 2, "name": "Services", "item": "{{ request()->root() }}/services" },
        { "@@type": "ListItem", "position": 3, "name": "Facility Management Services", "item": "{{ request()->url() }}" }
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
                <li class="breadcrumb-item active text-white">Facility Management Services</li>
            </ol>
        </nav>
        <div class="d-flex align-items-center gap-3 mb-3">
            <div class="icon-circle" style="background:rgba(255,255,255,0.15);color:#fff;width:64px;height:64px;font-size:1.8rem">
                <i class="bi bi-stars"></i>
            </div>
            <h1 class="display-5 fw-bold text-white mb-0">Facility Management Services</h1>
        </div>
        <p class="lead text-white-50">Professional, reliable and thorough cleaning solutions for every type of facility across Tanzania.</p>
    </div>
</section>

{{-- INTRO --}}
<section class="section-py bg-white">
    <div class="container">
        <div class="col-lg-8 mx-auto text-center">
            <h2 class="section-title mb-4">Spotless Spaces. <span>Productive Teams.</span></h2>
            <p class="text-muted fs-5">
                A clean, hygienic and well-maintained workplace is not just a matter of appearance — it is fundamental to employee
                health, productivity and your organisation's professional image. Our Facility Management Services division deploys trained,
                uniformed cleaners equipped with professional-grade equipment and eco-friendly cleaning products to deliver
                consistently outstanding results for our clients.
            </p>
        </div>
    </div>
</section>

{{-- SERVICES GRID --}}
<section class="section-py bg-light-blue">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Our Cleaning <span>Solutions</span></h2>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="card card-service h-100 p-4">
                    <div class="card-body">
                        <div class="icon-circle mb-3"><i class="bi bi-building-fill"></i></div>
                        <h5 class="fw-bold mb-2">Commercial Office Cleaning</h5>
                        <p class="text-muted small mb-0">
                            Daily, weekly and ad-hoc cleaning for offices, co-working spaces, reception areas, boardrooms and
                            common areas. Our teams work around your schedule — early morning, after-hours or weekends — to
                            minimise disruption to your business operations.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card card-service h-100 p-4">
                    <div class="card-body">
                        <div class="icon-circle mb-3"><i class="bi bi-tools"></i></div>
                        <h5 class="fw-bold mb-2">Industrial &amp; Warehouse Cleaning</h5>
                        <p class="text-muted small mb-0">
                            Heavy-duty cleaning for factories, warehouses, production floors and industrial facilities. We handle
                            oil, grease, chemical residues and dust using specialist equipment and cleaning agents, ensuring
                            a safe and compliant working environment at all times.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card card-service h-100 p-4">
                    <div class="card-body">
                        <div class="icon-circle mb-3"><i class="bi bi-cone-striped"></i></div>
                        <h5 class="fw-bold mb-2">Post-Construction Cleaning</h5>
                        <p class="text-muted small mb-0">
                            Comprehensive clean-up of newly built or renovated properties before handover or occupancy. We remove
                            construction debris, dust, paint splatter, cement residue and packaging materials — leaving your new
                            space pristine and ready for use.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card card-service h-100 p-4">
                    <div class="card-body">
                        <div class="icon-circle mb-3"><i class="bi bi-droplet-half"></i></div>
                        <h5 class="fw-bold mb-2">Specialised Deep Cleaning</h5>
                        <p class="text-muted small mb-0">
                            Intensive deep cleaning for kitchens, food processing areas, hospitals, clinics, schools and
                            other hygiene-critical environments. We use hospital-grade disinfectants and follow strict
                            protocols to eliminate bacteria, viruses and contaminants.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card card-service h-100 p-4">
                    <div class="card-body">
                        <div class="icon-circle mb-3"><i class="bi bi-recycle"></i></div>
                        <h5 class="fw-bold mb-2">Waste Management &amp; Disposal</h5>
                        <p class="text-muted small mb-0">
                            Organised waste collection, segregation and responsible disposal services for commercial and
                            industrial clients. We help businesses meet environmental compliance requirements and adopt
                            sustainable waste reduction practices.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 h-100 p-4 rounded-4 d-flex align-items-center justify-content-center text-center" style="background:var(--kk-blue)">
                    <div>
                        <i class="bi bi-check2-circle display-4 mb-3 text-white"></i>
                        <h5 class="fw-bold text-white mb-2">Quality Guaranteed</h5>
                        <p class="text-white-50 small mb-3">All our cleaning contracts include quality inspections and a satisfaction guarantee. Not happy? We come back and make it right.</p>
                        <a href="{{ route('contact.index') }}" class="btn btn-amber btn-sm fw-semibold px-4">Get a Quote</a>
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
                <h3 class="fw-bold mb-2">Get a Cleaning Quote Today</h3>
                <p class="text-white-50 mb-0">Tell us about your facility and cleaning requirements — we will prepare a competitive, no-obligation quote within 24 hours.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('contact.index') }}" class="btn btn-amber btn-lg px-5 fw-semibold">
                    <i class="bi bi-envelope-fill me-2"></i>Request Quote
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
