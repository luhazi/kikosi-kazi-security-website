@extends('layouts.public')
@section('title', 'Our Clients — Kikosi Kazi Security')
@section('meta_description', 'Kikosi Kazi Security is trusted by 200+ leading organisations across Tanzania — banks, embassies, NGOs, multinationals, government agencies and blue-chip firms. See the organisations that rely on us.')
@section('og_title', 'Our Clients — Trusted by 200+ Organisations | Kikosi Kazi Security')
@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ request()->root() }}" },
        { "@@type": "ListItem", "position": 2, "name": "Clients", "item": "{{ request()->url() }}" }
    ]
}
</script>
@endpush
@section('navbar-class', 'solid')

@section('content')

{{-- HERO --}}
<section class="py-5" style="background:linear-gradient(135deg,#0D47A1 0%,#1565C0 100%);min-height:220px;display:flex;align-items:center">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active text-white">Our Clients</li>
            </ol>
        </nav>
        <h1 class="display-5 fw-bold text-white mb-2">Our Clients</h1>
        <p class="lead text-white-50 mb-0">Trusted by leading organisations across Tanzania.</p>
    </div>
</section>

{{-- CLIENTS BY CATEGORY --}}
<section class="section-py bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Organisations That <span>Trust Us</span></h2>
            <div class="gold-bar mx-auto"></div>
            <p class="text-muted mt-3 mb-0">
                From banks and government agencies to hotels, NGOs and blue-chip companies —
                Kikosi Kazi Security is proud to serve {{ $clients->count() ? $clients->count() . '+' : 'a growing list of' }} valued clients.
            </p>
        </div>

        @if($clients->count())

        {{-- Category filter tabs --}}
        @php
            $categories = $clients->pluck('category')->unique()->sort()->values();
            // Map category slugs to display labels
            $catLabels = [
                'government'    => 'Government & Regulators',
                'banking'       => 'Banking & Finance',
                'hospitality'   => 'Hospitality & Tourism',
                'ngo'           => 'NGOs & Development',
                'healthcare'    => 'Healthcare',
                'manufacturing' => 'Manufacturing & Industry',
                'corporate'     => 'Corporate & Professional Services',
                'aviation'      => 'Aviation & Transport',
                'education'     => 'Education',
                'telecom'       => 'Telecom & Technology',
            ];
        @endphp

        <div class="d-flex flex-wrap justify-content-center gap-2 mb-4" id="clientTabs" role="tablist">
            <button class="btn btn-kk btn-sm px-3 py-2 active" data-cat="all" role="tab" aria-selected="true">All</button>
            @foreach($categories as $cat)
                <button class="btn btn-outline-kk btn-sm px-3 py-2" data-cat="{{ $cat }}" role="tab" aria-selected="false">
                    {{ $catLabels[$cat] ?? ucfirst($cat) }}
                </button>
            @endforeach
        </div>

        {{-- Logo grid --}}
        <div class="row g-3 g-md-4 justify-content-center" id="clientGrid">
            @foreach($clients as $client)
            <div class="col-6 col-sm-4 col-md-3 col-lg-2 client-item" data-category="{{ $client->category }}">
                @php $logo = asset('images/clients/' . basename($client->logo_path)); @endphp
                @if($client->website)
                <a href="{{ $client->website }}" target="_blank" rel="noopener" class="kk-client d-flex align-items-center justify-content-center rounded-3 h-100" title="{{ $client->name }}">
                    <img src="{{ $logo }}" alt="{{ $client->name }}" loading="lazy">
                </a>
                @else
                <div class="kk-client d-flex align-items-center justify-content-center rounded-3 h-100" title="{{ $client->name }}">
                    <img src="{{ $logo }}" alt="{{ $client->name }}" loading="lazy">
                </div>
                @endif
            </div>
            @endforeach
        </div>

        <div class="text-center mt-4">
            <p class="text-muted small mb-0" id="clientCount">
                Showing {{ $clients->count() }} clients
            </p>
        </div>

        @else
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
            <i class="bi bi-buildings display-4 text-muted mb-3"></i>
            <h5 class="fw-bold text-muted mb-2">Client list coming soon</h5>
            <p class="text-muted mb-0">We're updating our client showcase. Please check back shortly.</p>
        </div>
        @endif
    </div>
</section>

{{-- CTA --}}
<section class="py-5" style="background:var(--kk-blue)">
    <div class="container text-center">
        <h2 class="fw-bold text-white mb-3">Join Our Growing List of Satisfied Clients</h2>
        <p class="text-white-50 mb-4">Let's discuss how Kikosi Kazi Security can protect and support your organisation.</p>
        <a href="{{ route('contact.index') }}" class="btn btn-gold btn-lg px-5 fw-semibold">
            <i class="bi bi-chat-dots-fill me-2"></i>Talk to Us
        </a>
    </div>
</section>

@push('styles')
<style>
/* Category tab buttons */
#clientTabs .btn-kk { background:var(--kk-blue); color:#fff; }
#clientTabs .btn-kk.active,
#clientTabs .btn-outline-kk.active { background:var(--kk-blue); color:#fff; border-color:var(--kk-blue); }
#clientTabs .btn-outline-kk { color:var(--kk-blue); border-color:var(--kk-blue); }
#clientTabs .btn-outline-kk:hover { background:var(--kk-blue); color:#fff; }

/* Client cards */
.kk-client {
    background:#fff;
    border:1px solid #EEF2FB;
    padding:18px 14px;
    min-height:110px;
    transition: box-shadow .25s, transform .25s;
    text-decoration:none;
}
.kk-client:hover {
    box-shadow:0 10px 28px rgba(15,30,67,.10);
    transform:translateY(-3px);
}
.kk-client img {
    max-width:100%;
    max-height:70px;
    object-fit:contain;
    filter:grayscale(100%);
    opacity:.75;
    transition:filter .3s, opacity .3s;
}
.kk-client:hover img {
    filter:grayscale(0);
    opacity:1;
}

/* Hide non-matching items during filter */
.client-item[data-category] {
    transition: opacity .25s ease, transform .25s ease;
}
.client-item.hidden {
    display: none !important;
}
</style>
@endpush

@push('scripts')
<script>
(function(){
    var tabs = document.querySelectorAll('#clientTabs button[data-cat]');
    var items = document.querySelectorAll('#clientGrid .client-item');
    var countEl = document.getElementById('clientCount');

    if (!tabs.length) return;

    function filter(cat) {
        var visible = 0;
        items.forEach(function(el) {
            if (cat === 'all' || el.getAttribute('data-category') === cat) {
                el.classList.remove('hidden');
                visible++;
            } else {
                el.classList.add('hidden');
            }
        });
        if (countEl) countEl.textContent = 'Showing ' + visible + ' client' + (visible !== 1 ? 's' : '');
    }

    tabs.forEach(function(btn) {
        btn.addEventListener('click', function() {
            tabs.forEach(function(b) {
                b.classList.remove('active');
                b.setAttribute('aria-selected', 'false');
            });
            btn.classList.add('active');
            btn.setAttribute('aria-selected', 'true');
            filter(btn.getAttribute('data-cat'));
        });
    });
})();
</script>
@endpush

@endsection
