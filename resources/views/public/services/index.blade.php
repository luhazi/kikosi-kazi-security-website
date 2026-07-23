@extends('layouts.public')
@section('title', 'Our Services — Kikosi Kazi Security')
@section('meta_description', 'Explore Kikosi Kazi Security\'s four integrated divisions: Security & Risk Management, Human Capital Solutions, Insurance Advisory & Brokerage, and Facility Management Services — tailored for Tanzanian businesses.')
@section('og_title', 'Our Services — Four Integrated Divisions | Kikosi Kazi Security')
@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ request()->root() }}" },
        { "@@type": "ListItem", "position": 2, "name": "Services", "item": "{{ request()->url() }}" }
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
                <li class="breadcrumb-item active text-white">Services</li>
            </ol>
        </nav>
        <h1 class="display-5 fw-bold text-white mb-2">Our Services</h1>
        <p class="lead text-white-50">Four integrated divisions. One trusted partner.</p>
    </div>
</section>

{{-- SERVICE CARDS (managed from Admin → CMS → Services) --}}
<section class="section-py bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Comprehensive <span>Service Solutions</span></h2>
            <p class="text-muted">Tailored professional services for businesses of all sizes across Tanzania.</p>
        </div>

        @php
            // Division metadata — heading, icon, colour and a short intro per category.
            $catMeta = [
                'security'  => ['label'=>'Security & Risk Management',  'icon'=>'shield-fill-check', 'color'=>'#0F1E43',
                    'blurb'=>'TSIA-registered, PSCGP-compliant protection for your people, property and assets — uniformed guards, patrols, CCTV, VIP protection and rapid response, 24/7.'],
                'hr'        => ['label'=>'Human Capital Solutions',      'icon'=>'people-fill',       'color'=>'#1565C0',
                    'blurb'=>'Recruitment, payroll, training and labour-law compliance — everything you need to build and manage a great team, aligned with Tanzanian law.'],
                'insurance' => ['label'=>'Insurance Advisory & Brokerage', 'icon'=>'umbrella-fill',     'color'=>'#B8860B',
                    'blurb'=>'Group life, WCF, motor, property and specialised covers — the right protection at the most competitive premiums, with a partner who fights your claims.'],
                'cleaning'  => ['label'=>'Facility Management Services',   'icon'=>'stars',             'color'=>'#2E7D32',
                    'blurb'=>'Office, industrial and post-construction cleaning, deep sanitisation and waste management — spotless, hygienic facilities every day.'],
            ];
        @endphp

        @foreach($catMeta as $catKey => $meta)
        @php $group = $services[$catKey] ?? collect(); @endphp
        <div class="mb-5">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <h3 class="fw-bold mb-0" style="color:{{ $meta['color'] }}">
                    <i class="bi bi-{{ $meta['icon'] }} me-2"></i>{{ $meta['label'] }}
                </h3>
                <a href="{{ route('services.show', $catKey) }}" class="btn btn-outline-primary btn-sm">
                    Explore {{ $meta['label'] }} <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <p class="text-muted mb-4" style="max-width:820px">{{ $meta['blurb'] }}</p>

            @if($group->count())
            <div class="row g-4">
                @foreach($group as $svc)
                <div class="col-lg-4 col-md-6">
                    <div class="card card-service h-100 p-4">
                        <div class="card-body">
                            <div class="icon-circle mb-3" style="color:{{ $meta['color'] }};background:{{ $meta['color'] }}14">
                                <i class="bi bi-{{ $svc->icon ?: $meta['icon'] }}"></i>
                            </div>
                            <h5 class="fw-bold mb-2" style="color:var(--kk-blue)">{{ $svc->title }}</h5>
                            <p class="text-muted small mb-0">{{ $svc->description }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        @if(!$loop->last)<hr class="my-5" style="border-top:1px solid #EEF2FB">@endif
        @endforeach
    </div>
</section>

{{-- CTA BAND --}}
<section class="py-5" style="background:var(--kk-blue)">
    <div class="container text-center">
        <h2 class="fw-bold text-white mb-3">Not Sure Which Service You Need?</h2>
        <p class="text-white-50 mb-4">Our consultants will help you identify the right solution for your business.</p>
        <a href="{{ route('contact.index') }}" class="btn btn-amber btn-lg px-5 fw-semibold">
            <i class="bi bi-chat-dots-fill me-2"></i>Talk to a Consultant
        </a>
    </div>
</section>

@endsection
