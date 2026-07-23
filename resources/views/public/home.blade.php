@extends('layouts.public')
@section('title', 'Kikosi Kazi Security — Integrated Business Solutions')
@section('meta_description', 'Kikosi Kazi Security is Tanzania\'s trusted integrated security and business support partner — Security & Risk Management, Human Capital Solutions, Insurance Advisory and Facility Management. TSIA-registered, PSCGP-compliant. Request a free consultation.')
@section('og_title', 'Kikosi Kazi Security — Security, HR, Insurance & Facility Solutions in Tanzania')
@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "ProfessionalService",
    "name": "Kikosi Kazi Security",
    "image": "{{ asset('images/logo.png') }}",
    "url": "{{ request()->root() }}",
    "telephone": "+255700000000",
    "email": "info@kikosikazi.co.tz",
    "priceRange": "$$",
    "description": "Integrated security and business support services across Tanzania.",
    "address": { "@@type": "PostalAddress", "addressLocality": "Dar es Salaam", "addressCountry": "TZ" },
    "areaServed": "Tanzania",
    "memberOf": ["Tanzania Security Industry Association (TSIA)", "Private Security Companies Governance Portal (PSCGP)"]
}
</script>
@endpush

@section('content')

<style>
/* ── Hero background slideshow (video + image mix) ── */
.hero-media, .hero-overlay { position:absolute; inset:0; }
.hero-media   { z-index:0; }
.hero-slide {
    position:absolute; inset:0;
    transform:translateX(100%);                             /* parked off-screen right */
    transition:transform 1.05s cubic-bezier(.65,0,.35,1);
    will-change:transform;
}
/* Two-layer composite so BOTH portrait & landscape photos look good:
   a blurred, darkened cover backdrop fills the frame; the sharp photo sits
   on top, "contained". Landscape photos fill fully (backdrop hidden);
   portrait photos show with a soft blurred surround instead of hard cropping. */
.hero-slide .hs-bg,
.hero-slide .hs-fg { position:absolute; inset:0; background-repeat:no-repeat; background-position:center; }
.hero-slide .hs-bg { background-size:cover; filter:blur(26px) brightness(.5); transform:scale(1.12); }
.hero-slide .hs-fg { background-size:contain; }
.hero-slide video  { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; }

.hero-overlay {
    z-index:1;
    background:
      linear-gradient(135deg, rgba(15,30,67,.92) 0%, rgba(15,30,67,.70) 42%, rgba(21,101,192,.42) 100%),
      linear-gradient(to top, rgba(15,30,67,.55), rgba(15,30,67,0) 42%);
}
/* manual prev / next arrows */
.hero-nav {
    position:absolute; z-index:3; bottom:104px;
    width:46px; height:46px; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    border:1px solid rgba(255,255,255,.35); background:rgba(15,30,67,.4);
    color:#fff; font-size:1.2rem; cursor:pointer;
    transition:background .2s, color .2s, border-color .2s;
}
.hero-nav:hover { background:var(--kk-gold); color:var(--kk-blue); border-color:transparent; }
.hero-prev { right:86px; }
.hero-next { right:32px; }
@media (max-width:991.98px) { .hero-nav { display:none; } }
@media (prefers-reduced-motion: reduce) { .hero-slide { transition:none; } }
</style>

{{-- ═══════════════════════════════════════════════════════
     SECTION 1 — HERO
═══════════════════════════════════════════════════════ --}}
@php
    // Auto-discover whatever media sits in public/media/hero/ — any filename or
    // extension. Drop photos (.jpg/.png/.webp) or clips (.mp4) in and they appear;
    // no need to match exact names. Files starting with "_" are ignored (helpers).
    $heroDir   = public_path('media/hero');
    $heroPaths = [];
    foreach (['jpg','jpeg','png','webp','JPG','JPEG','PNG','WEBP','mp4','MP4'] as $e) {
        $heroPaths = array_merge($heroPaths, glob($heroDir.DIRECTORY_SEPARATOR.'*.'.$e) ?: []);
    }
    $heroPaths = array_values(array_filter(array_unique($heroPaths), function ($p) {
        return substr(basename($p), 0, 1) !== '_';
    }));
    sort($heroPaths, SORT_NATURAL | SORT_FLAG_CASE);
    $heroSlides = [];
    foreach ($heroPaths as $p) {
        $isVid = strtolower(pathinfo($p, PATHINFO_EXTENSION)) === 'mp4';
        $heroSlides[] = ['type' => $isVid ? 'video' : 'image', 'src' => asset('media/hero/'.rawurlencode(basename($p)))];
    }
@endphp
<section class="hero-home" style="
    min-height: 100vh;
    background: linear-gradient(135deg, #0F1E43 0%, #1565C0 55%, #1E88E5 100%);
    display: flex;
    align-items: center;
    position: relative;
    overflow: hidden;
    margin-top: -72px;
    padding-top: 72px;
">
    {{-- ── Background slideshow: mix of video clips and images ── --}}
    <div class="hero-media" aria-hidden="true">
        @foreach($heroSlides as $i => $slide)
            @if($slide['type'] === 'video')
                <div class="hero-slide" @if($i === 0) style="transform:translateX(0)" @endif>
                    <video muted loop playsinline preload="auto" @if(!empty($slide['poster'])) poster="{{ $slide['poster'] }}" @endif>
                        <source src="{{ $slide['src'] }}" type="video/mp4">
                    </video>
                </div>
            @else
                <div class="hero-slide" @if($i === 0) style="transform:translateX(0)" @endif>
                    <div class="hs-bg" style="background-image:url('{{ $slide['src'] }}')"></div>
                    <div class="hs-fg" style="background-image:url('{{ $slide['src'] }}')"></div>
                </div>
            @endif
        @endforeach
    </div>
    {{-- dark overlay keeps the headline legible over any media --}}
    <div class="hero-overlay" aria-hidden="true"></div>

    {{-- manual slide controls --}}
    <button type="button" class="hero-nav hero-prev" aria-label="Previous slide"><i class="bi bi-chevron-left"></i></button>
    <button type="button" class="hero-nav hero-next" aria-label="Next slide"><i class="bi bi-chevron-right"></i></button>

    {{-- decorative blobs --}}
    <div style="position:absolute;top:-120px;right:-120px;width:520px;height:520px;border-radius:50%;background:rgba(255,255,255,.04);pointer-events:none;z-index:1"></div>
    <div style="position:absolute;bottom:-80px;right:18%;width:280px;height:280px;border-radius:50%;background:rgba(212,175,55,.08);pointer-events:none;z-index:1"></div>

    <div class="container position-relative" style="padding: 80px 0;z-index:2">
        <div class="row align-items-center g-5">

            {{-- LEFT: text --}}
            <div class="col-lg-6">
                <span class="eyebrow">Tanzania's Trusted Integrated Services Partner</span>

                <h1 class="display-hero text-white mb-4">
                    Protecting People.<br>
                    Supporting Business.<br>
                    <span style="color:var(--kk-gold)">Securing Assets.</span>
                </h1>

                <p style="font-size:1.15rem;color:rgba(255,255,255,.75);line-height:1.8;max-width:480px;margin-bottom:2rem">
                    Kikosi Kazi Security delivers world-class security, human capital, insurance and facility management services to 200+ clients across Tanzania — from SMEs to multinationals.
                </p>

                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('contact.index') }}" class="btn btn-gold btn-lg px-5">
                        Get a Free Consultation
                    </a>
                    <a href="{{ route('about') }}" class="btn btn-outline-white btn-lg px-5">
                        Our Story
                    </a>
                </div>

                <div class="d-flex flex-wrap gap-4 mt-5" style="color:rgba(255,255,255,.65);font-size:.85rem">
                    <span><i class="bi bi-patch-check-fill me-2" style="color:var(--kk-gold)"></i>TSIA &amp; PSCGP Registered</span>
                    <span><i class="bi bi-award-fill me-2" style="color:var(--kk-gold)"></i>10+ Years Experience</span>
                    <span><i class="bi bi-geo-alt-fill me-2" style="color:var(--kk-gold)"></i>Pan-Tanzania Operations</span>
                </div>
            </div>

            {{-- RIGHT: stat boxes --}}
            <div class="col-lg-6">
                <div class="row g-3">
                    @foreach([
                        ['value'=>'500+', 'label'=>'Guards Deployed',    'icon'=>'shield-fill-check'],
                        ['value'=>'200+', 'label'=>'Clients Served',     'icon'=>'buildings-fill'],
                        ['value'=>'10+',  'label'=>'Years of Excellence','icon'=>'calendar3-fill'],
                        ['value'=>'24/7', 'label'=>'Emergency Response', 'icon'=>'clock-history'],
                    ] as $s)
                    <div class="col-6">
                        <div style="background:rgba(255,255,255,.08);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,.12);border-radius:var(--kk-radius);padding:28px 24px">
                            <i class="bi bi-{{ $s['icon'] }} fs-2 mb-2 d-block" style="color:var(--kk-gold)"></i>
                            <div class="stat-number">{{ $s['value'] }}</div>
                            <div class="stat-label">{{ $s['label'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    <div style="position:absolute;bottom:28px;left:50%;transform:translateX(-50%);text-align:center">
        <div style="color:rgba(255,255,255,.35);font-size:.7rem;letter-spacing:.12em;text-transform:uppercase;margin-bottom:6px">Scroll</div>
        <div style="width:1px;height:36px;background:rgba(255,255,255,.2);margin:0 auto"></div>
    </div>
</section>

<script>
// ── Hero background slideshow: horizontal slide, auto + manual arrows ──
document.addEventListener('DOMContentLoaded', function () {
    var media = document.querySelector('.hero-media');
    if (!media) return;
    var slides = [].slice.call(media.querySelectorAll('.hero-slide'));
    var n = slides.length;
    if (!n) return;

    var idx = 0, timer = null, DURATION = 6000;

    function playIfVideo(s, on) {
        var v = s.querySelector('video'); if (!v) return;
        if (on) { try { v.currentTime = 0; v.play(); } catch (e) {} }
        else if (v.pause) { v.pause(); }
    }
    function setX(el, x, animate) {
        el.style.transition = animate ? '' : 'none';
        el.style.transform = 'translateX(' + x + ')';
    }

    // park every slide off-screen right, show the first
    slides.forEach(function (s, i) { setX(s, i === 0 ? '0' : '100%', false); });
    void media.offsetWidth;
    slides.forEach(function (s) { s.style.transition = ''; });
    playIfVideo(slides[0], true);

    // dir = +1 → new slide enters from right; dir = -1 → enters from left
    function go(next, dir) {
        if (next === idx || n < 2) return;
        var cur = slides[idx], nxt = slides[next];
        setX(nxt, dir > 0 ? '100%' : '-100%', false); // place incoming on entry side
        void nxt.offsetWidth;                          // flush (no visible jump, it's off-screen)
        setX(cur, dir > 0 ? '-100%' : '100%', true);   // current slides away
        setX(nxt, '0', true);                          // incoming slides in
        playIfVideo(cur, false); playIfVideo(nxt, true);
        idx = next;
    }
    function next() { go((idx + 1) % n, 1); }
    function prev() { go((idx - 1 + n) % n, -1); }

    function start() { stop(); timer = setInterval(next, DURATION); }
    function stop()  { if (timer) { clearInterval(timer); timer = null; } }

    var nb = document.querySelector('.hero-next');
    var pb = document.querySelector('.hero-prev');
    if (nb) nb.addEventListener('click', function () { next(); start(); }); // reset timer on manual nav
    if (pb) pb.addEventListener('click', function () { prev(); start(); });

    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (n >= 2 && !reduce) start();
});
</script>


{{-- ═══════════════════════════════════════════════════════
     SECTION 2 — FOUR BUSINESS DIVISIONS
═══════════════════════════════════════════════════════ --}}
<section class="section-py bg-off-white">
    <div class="container">
        <div class="text-center mb-5">
            <span class="eyebrow">What We Do</span>
            <h2 class="section-title">Four Specialist Divisions.<br>One Trusted Partner.</h2>
            <div class="gold-bar mx-auto"></div>
            <p class="section-subtitle mt-3">Everything your organisation needs to operate safely, compliantly and efficiently — under one roof.</p>
        </div>

        <div class="row g-4">
            @foreach([
                ['icon'=>'shield-lock-fill','title'=>'Security & Risk Management',  'color'=>'#0F1E43','link'=>route('services.show','security'),
                 'desc'=>'Sleep easy knowing TSIA-registered, PSCGP-compliant guards, rapid-response teams and 24/7 CCTV stand between your people, premises and risk — trusted by banks, embassies and blue-chip firms nationwide.'],
                ['icon'=>'people-fill',     'title'=>'Human Capital Solutions',      'color'=>'#1565C0','link'=>route('services.show','hr'),
                 'desc'=>'Hire faster, stay compliant and end payroll headaches. From recruitment and payroll to labour-law advisory, we run your people operations so you can focus on growth — fully aligned with Tanzanian law.'],
                ['icon'=>'umbrella-fill',   'title'=>'Insurance Advisory & Brokerage', 'color'=>'#B8860B','link'=>route('services.show','insurance'),
                 'desc'=>'Protect what you have built. We secure the right group life, WCF, motor and property cover at the most competitive premiums — and fight your corner at claim time so you are never left exposed.'],
                ['icon'=>'stars',      'title'=>'Facility Management Services',   'color'=>'#2E7D32','link'=>route('services.show','cleaning'),
                 'desc'=>'Make the right impression every day. Our trained crews deliver spotless offices, deep sanitisation and dependable waste management — the kind of hygienic, professional space your staff and clients notice.'],
            ] as $div)
            <div class="col-lg-3 col-md-6">
                <div class="card-service h-100 d-flex flex-column p-4">
                    <div class="mb-4">
                        <div style="width:60px;height:60px;border-radius:14px;background:{{ $div['color'] }}18;display:flex;align-items:center;justify-content:center;font-size:1.7rem;color:{{ $div['color'] }}">
                            <i class="bi bi-{{ $div['icon'] }}"></i>
                        </div>
                    </div>
                    <h4 style="font-family:'Montserrat',sans-serif;font-weight:700;font-size:1.15rem;color:var(--kk-blue);margin-bottom:.75rem">{{ $div['title'] }}</h4>
                    <p class="text-muted flex-fill" style="font-size:.95rem;line-height:1.75">{{ $div['desc'] }}</p>
                    <a href="{{ $div['link'] }}" class="btn btn-kk btn-sm align-self-start px-4 mt-3">
                        Learn More <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════════
     SECTION 3 — ABOUT STRIP
═══════════════════════════════════════════════════════ --}}
<section class="section-py bg-white">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5">
                <div style="background:var(--kk-blue);border-radius:var(--kk-radius-lg);padding:48px 40px;color:#fff;position:relative;overflow:hidden">
                    <div style="position:absolute;top:-40px;right:-40px;width:160px;height:160px;border-radius:50%;background:rgba(212,175,55,.15)"></div>
                    <div class="position-relative">
                        <div class="stat-number mb-1">10+</div>
                        <div style="font-size:1rem;color:rgba(255,255,255,.7);margin-bottom:2rem">Years Serving Tanzania</div>
                        <div class="row g-3 text-center">
                            @foreach([['200+','Clients'],['500+','Staff'],['4','Divisions'],['24/7','Support']] as $s)
                            <div class="col-6">
                                <div style="background:rgba(255,255,255,.08);border-radius:12px;padding:20px 12px">
                                    <div style="font-family:'Montserrat',sans-serif;font-size:1.7rem;font-weight:800;color:var(--kk-gold)">{{ $s[0] }}</div>
                                    <div style="font-size:.8rem;color:rgba(255,255,255,.65)">{{ $s[1] }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <span class="eyebrow">About Kikosi Kazi</span>
                <h2 class="section-title mb-3">From a Security Company<br>to Tanzania's <span>Leading Integrated Services Provider</span></h2>
                <div class="gold-bar"></div>
                <p class="text-muted mb-4" style="font-size:1.05rem;line-height:1.85">
                    Founded in Dar es Salaam with a single vision — to deliver world-class integrated services to Tanzanian businesses — Kikosi Kazi has grown from a small security outfit to a full-service corporate partner trusted by multinationals, NGOs and government agencies alike.
                </p>
                <p class="text-muted mb-5" style="font-size:1.05rem;line-height:1.85">
                    Today, our four specialist divisions — Security, HR, Insurance and Cleaning — operate as a seamless unit, giving clients a single accountable partner for all their workforce and facility needs.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('about') }}" class="btn btn-kk px-5">Our Full Story</a>
                    <a href="{{ route('contact.index') }}" class="btn btn-outline-secondary px-5">Talk to Us</a>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════════
     SECTION 4 — WHY CHOOSE US
═══════════════════════════════════════════════════════ --}}
<section class="section-py bg-off-white">
    <div class="container">
        <div class="text-center mb-5">
            <span class="eyebrow">Why Kikosi Kazi</span>
            <h2 class="section-title">The Standard Others <span>Measure Against</span></h2>
            <div class="gold-bar mx-auto"></div>
        </div>
        <div class="row g-4">
            @foreach([
                ['icon'=>'person-badge-fill',    'title'=>'Trained Professionals',  'desc'=>'Every member is background-checked, licensed where required, and trained to our rigorous standards before deployment.'],
                ['icon'=>'puzzle-fill',          'title'=>'Integrated Solutions',    'desc'=>'Security, HR, insurance and cleaning under one contract — seamless coordination, single billing, unified account manager.'],
                ['icon'=>'lightning-charge-fill','title'=>'Rapid Response',          'desc'=>'Our 24/7 command centre and nationwide field teams ensure any incident is contained and resolved fast.'],
                ['icon'=>'map-fill',             'title'=>'Pan-Tanzania Coverage',   'desc'=>'From Dar es Salaam to Mwanza, Arusha to Dodoma — we have the reach to serve you wherever you operate.'],
                ['icon'=>'graph-up-arrow',  'title'=>'Measurable Results',      'desc'=>'Clear KPIs, regular reporting and continuous improvement so you always know you are getting full value.'],
                ['icon'=>'people-fill',     'title'=>'Long-Term Partnerships',  'desc'=>'95% client retention. We build relationships, not just contracts — your success is our success.'],
            ] as $item)
            <div class="col-lg-4 col-md-6">
                <div class="d-flex gap-4 align-items-start">
                    <div style="width:54px;height:54px;border-radius:14px;background:var(--kk-blue);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:1.35rem;color:var(--kk-gold)">
                        <i class="bi bi-{{ $item['icon'] }}"></i>
                    </div>
                    <div>
                        <h5 style="font-family:'Montserrat',sans-serif;font-weight:700;font-size:1.02rem;color:var(--kk-blue);margin-bottom:.5rem">{{ $item['title'] }}</h5>
                        <p class="text-muted mb-0" style="font-size:.93rem;line-height:1.75">{{ $item['desc'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════════
     SECTION 5 — SERVICES (alternating)
═══════════════════════════════════════════════════════ --}}
<section class="section-py bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <span class="eyebrow">Our Services</span>
            <h2 class="section-title">What's Included in <span>Each Division</span></h2>
            <div class="gold-bar mx-auto"></div>
        </div>

        @php
        $svcs = [
            ['cat'=>'security','title'=>'Security & Risk Management','icon'=>'shield-lock-fill','color'=>'#0F1E43','bg'=>'#E8F0FE','link'=>route('services.show','security'),
             'tag'=>'Round-the-clock protection you can trust — for your people, property and peace of mind.',
             'pts'=>['Uniformed Guards & Patrol Teams','VIP Close Protection','CCTV & Access Control Systems','Alarm Response & Incident Management','Event Security & Crowd Management']],
            ['cat'=>'hr','title'=>'Human Capital Solutions','icon'=>'people-fill','color'=>'#1565C0','bg'=>'#E3F2FD','link'=>route('services.show','hr'),
             'tag'=>'Build a stronger team while we handle recruitment, payroll and compliance for you.',
             'pts'=>['Talent Acquisition & Recruitment','Payroll Processing & NSSF Compliance','Staff Training & Development','HR Policy Development','Labour Law Advisory']],
            ['cat'=>'insurance','title'=>'Insurance Advisory & Brokerage','icon'=>'umbrella-fill','color'=>'#B8860B','bg'=>'#FFFDE7','link'=>route('services.show','insurance'),
             'tag'=>'The right cover, the best premiums, and a partner who fights your claims.',
             'pts'=>['Group Life & Medical Cover','WCF Workers\' Compensation','Motor Fleet Insurance','Property & Fire Cover','Personalised Portfolio Management']],
            ['cat'=>'cleaning','title'=>'Facility Management Services','icon'=>'stars','color'=>'#2E7D32','bg'=>'#E8F5E9','link'=>route('services.show','cleaning'),
             'tag'=>'Spotless, hygienic spaces that make the right impression every single day.',
             'pts'=>['Daily Office & Commercial Cleaning','Deep Sanitisation & Disinfection','Industrial & Warehouse Cleaning','Waste Management & Disposal','Post-Construction Clean-up']],
        ];
        @endphp

        @foreach($svcs as $i => $svc)
        <div class="row align-items-center g-5 {{ $i > 0 ? 'mt-0' : '' }} {{ $i % 2 == 1 ? 'flex-lg-row-reverse' : '' }}">
            <div class="col-lg-5">
                <div style="background:{{ $svc['bg'] }};border-radius:var(--kk-radius-lg);padding:60px;text-align:center;display:flex;align-items:center;justify-content:center;flex-direction:column;min-height:260px">
                    <i class="bi bi-{{ $svc['icon'] }}" style="font-size:5rem;color:{{ $svc['color'] }};opacity:.8;display:block;margin-bottom:14px"></i>
                    <div style="font-family:'Montserrat',sans-serif;font-weight:700;font-size:1.05rem;color:{{ $svc['color'] }}">{{ $svc['title'] }}</div>
                </div>
            </div>
            <div class="col-lg-7">
                <span class="eyebrow">Division {{ sprintf('%02d', $i+1) }}</span>
                <h3 style="font-family:'Montserrat',sans-serif;font-weight:800;font-size:1.8rem;color:var(--kk-blue);margin-bottom:.6rem">{{ $svc['title'] }}</h3>
                <div class="gold-bar"></div>
                <p class="mt-3 mb-3" style="font-size:1.05rem;font-weight:600;color:{{ $svc['color'] }};line-height:1.6">{{ $svc['tag'] }}</p>
                @php
                    // Use CMS-managed service titles once a division has enough of them (3+),
                    // otherwise keep the polished default list so the homepage never looks sparse.
                    $cmsPts = ($cmsServices[$svc['cat']] ?? collect())->pluck('title')->filter()->values()->all();
                    $pts = count($cmsPts) >= 3 ? $cmsPts : $svc['pts'];
                @endphp
                <ul class="list-unstyled mb-4">
                    @foreach($pts as $pt)
                    <li class="d-flex align-items-center gap-3 mb-3">
                        <i class="bi bi-check2-circle" style="color:{{ $svc['color'] }};font-size:1.1rem;flex-shrink:0"></i>
                        <span class="text-muted" style="font-size:1rem">{{ $pt }}</span>
                    </li>
                    @endforeach
                </ul>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ $svc['link'] }}" class="btn btn-kk px-4">
                        Explore {{ $svc['title'] }} <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                    <a href="{{ route('contact.index') }}" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-telephone-fill me-2"></i>Get a Free Quote
                    </a>
                </div>
            </div>
        </div>
        @if(!$loop->last)
        <hr class="my-5 border-0" style="border-top:1px solid #EEF2FB !important">
        @endif
        @endforeach
    </div>
</section>


{{-- ═══════════════════════════════════════════════════════
     SECTION 6 — STATISTICS BANNER
═══════════════════════════════════════════════════════ --}}
<section style="background:linear-gradient(135deg,#0F1E43 0%,#1565C0 100%);padding:72px 0">
    <div class="container">
        <div class="row g-4 text-center">
            @foreach([
                ['value'=>'500+','label'=>'Trained & Deployed Staff',   'icon'=>'person-check-fill'],
                ['value'=>'200+','label'=>'Active Corporate Clients',   'icon'=>'buildings-fill'],
                ['value'=>'10+', 'label'=>'Years of Proven Excellence', 'icon'=>'calendar-check-fill'],
                ['value'=>'24/7','label'=>'Emergency Operations',       'icon'=>'headset'],
                ['value'=>'4',   'label'=>'Specialist Divisions',       'icon'=>'grid-fill'],
                ['value'=>'95%', 'label'=>'Client Retention Rate',      'icon'=>'heart-fill'],
            ] as $stat)
            <div class="col-lg-2 col-md-4 col-6">
                <i class="bi bi-{{ $stat['icon'] }}" style="font-size:1.8rem;color:var(--kk-gold);opacity:.75;margin-bottom:10px;display:block"></i>
                <div class="stat-number">{{ $stat['value'] }}</div>
                <div class="stat-label">{{ $stat['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════════
     SECTION 7 — CAREERS CTA
═══════════════════════════════════════════════════════ --}}
<section class="section-py bg-off-white">
    <div class="container">
        <div class="row align-items-end mb-5">
            <div class="col-lg-7">
                <span class="eyebrow">Open Positions</span>
                <h2 class="section-title mb-2">Join the Kikosi Kazi <span>Family</span></h2>
                <div class="gold-bar"></div>
                <p class="text-muted" style="font-size:1.05rem">We are always looking for talented, dedicated professionals. Register today to apply for current vacancies.</p>
            </div>
            <div class="col-lg-5 text-lg-end">
                <a href="{{ route('careers.index') }}" class="btn btn-kk btn-lg px-5">
                    View All Vacancies <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        </div>

        @if(isset($latestJobs) && $latestJobs->count())
        <div class="row g-4">
            @foreach($latestJobs as $job)
            <div class="col-lg-4 col-md-6">
                <div class="card-service p-4 h-100 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge badge-dept px-3 py-2 rounded-pill">{{ $job->department }}</span>
                        @if(isset($job->job_type) && $job->job_type === 'client')
                        <span class="badge rounded-pill" style="background:#E3F2FD;color:#1565C0;font-size:.72rem">{{ $job->client_name ?? 'Client' }}</span>
                        @else
                        <span class="badge rounded-pill" style="background:#E8F5E9;color:#2E7D32;font-size:.72rem">Kikosi Kazi</span>
                        @endif
                    </div>
                    <h5 style="font-family:'Montserrat',sans-serif;font-weight:700;color:var(--kk-blue);margin-bottom:.6rem">{{ $job->title }}</h5>
                    <div class="text-muted small mb-4 d-flex gap-3 flex-wrap">
                        <span><i class="bi bi-geo-alt-fill me-1"></i>{{ $job->location }}</span>
                        <span><i class="bi bi-people-fill me-1"></i>{{ $job->vacancies }} post{{ $job->vacancies != 1 ? 's' : '' }}</span>
                    </div>
                    <div class="d-flex gap-2 mt-auto">
                        <a href="{{ route('careers.show', $job) }}" class="btn btn-outline-primary btn-sm flex-fill">Details</a>
                        <a href="{{ route('careers.show', $job) }}" class="btn btn-gold btn-sm flex-fill fw-semibold">Apply</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5">
            <i class="bi bi-briefcase-fill" style="font-size:3rem;color:var(--kk-blue);opacity:.25;display:block;margin-bottom:16px"></i>
            <p class="text-muted mb-4">New vacancies are posted regularly. Register to be the first to know.</p>
            <a href="{{ route('register') }}" class="btn btn-kk px-5">Create an Account</a>
        </div>
        @endif
    </div>
</section>


{{-- ═══════════════════════════════════════════════════════
     SECTION 8 — TESTIMONIALS
═══════════════════════════════════════════════════════ --}}
@if(isset($testimonials) && $testimonials->count())
<section class="section-py bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <span class="eyebrow">Client Testimonials</span>
            <h2 class="section-title">What Our Clients <span>Say</span></h2>
            <div class="gold-bar mx-auto"></div>
        </div>
        <div class="row g-4">
            @foreach($testimonials->take(3) as $t)
            <div class="col-lg-4 col-md-6">
                <div class="card-service p-5 h-100 d-flex flex-column">
                    <i class="bi bi-quote" style="font-size:2.5rem;color:var(--kk-gold);opacity:.45;display:block;margin-bottom:16px"></i>
                    <p class="text-muted flex-fill" style="font-size:1.02rem;line-height:1.85;font-style:italic">"{{ $t->quote }}"</p>
                    <div class="d-flex align-items-center gap-3 mt-4 pt-4 border-top">
                        <div style="width:44px;height:44px;border-radius:50%;background:var(--kk-blue);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <i class="bi bi-person-fill text-white"></i>
                        </div>
                        <div>
                            <div style="font-family:'Montserrat',sans-serif;font-weight:700;font-size:.9rem;color:var(--kk-blue)">{{ $t->client_name }}</div>
                            @if($t->company)<div class="text-muted" style="font-size:.8rem">{{ $t->company }}</div>@endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif


{{-- ═══════════════════════════════════════════════════════
     SECTION 9 — CONTACT CTA BANNER
═══════════════════════════════════════════════════════ --}}
<section style="background:linear-gradient(135deg,#0F1E43 0%,#1565C0 100%);padding:80px 0;overflow:hidden;position:relative">
    <div style="position:absolute;right:-80px;top:-80px;width:300px;height:300px;border-radius:50%;background:rgba(212,175,55,.08);pointer-events:none"></div>
    <div class="container text-center position-relative">
        <span class="eyebrow" style="color:var(--kk-gold)">Ready to Get Started?</span>
        <h2 style="font-family:'Montserrat',sans-serif;font-size:clamp(1.8rem,3vw,2.8rem);font-weight:800;color:#fff;margin-bottom:.75rem">
            Let's Build a Safer, Stronger<br><span style="color:var(--kk-gold)">Organisation Together</span>
        </h2>
        <p style="color:rgba(255,255,255,.7);font-size:1.1rem;max-width:520px;margin:0 auto 2.5rem">
            Talk to our team today about your specific needs. We offer a free initial consultation with no obligation.
        </p>
        <div class="d-flex justify-content-center flex-wrap gap-3">
            <a href="{{ route('contact.index') }}" class="btn btn-gold btn-lg px-5">
                <i class="bi bi-chat-dots-fill me-2"></i>Contact Us Now
            </a>
            <a href="tel:+255700000000" class="btn btn-outline-white btn-lg px-5">
                <i class="bi bi-telephone-fill me-2"></i>Call Us
            </a>
        </div>
    </div>
</section>

@endsection
