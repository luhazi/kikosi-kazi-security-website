<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Kikosi Kazi Security')</title>
<meta name="description" content="@yield('meta_description', 'Kikosi Kazi Security — integrated Security & Risk Management, Human Capital Solutions, Insurance Advisory and Facility Management services across Tanzania.')">
<meta name="author" content="Kikosi Kazi Security">

{{-- Canonical & social meta. request()->url() keeps these correct on any domain without hard-coding. --}}
<link rel="canonical" href="{{ request()->url() }}">
<meta property="og:type" content="website">
<meta property="og:site_name" content="Kikosi Kazi Security">
<meta property="og:title" content="@yield('og_title', 'Kikosi Kazi Security')">
<meta property="og:description" content="@yield('meta_description', 'Kikosi Kazi Security — integrated Security & Risk Management, Human Capital Solutions, Insurance Advisory and Facility Management services across Tanzania.')">
<meta property="og:url" content="{{ request()->url() }}">
<meta property="og:locale" content="en_GB">
{{-- TODO: create a 1200x630 social share image at public/images/og-image.jpg --}}
<meta property="og:image" content="{{ asset('images/og-image.jpg') }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="@yield('og_title', 'Kikosi Kazi Security')">
<meta name="twitter:description" content="@yield('meta_description', 'Kikosi Kazi Security — integrated security and business support services across Tanzania.')">
<meta name="twitter:image" content="{{ asset('images/og-image.jpg') }}">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@stack('styles')
<style>
:root {
    --kk-blue:      #0F1E43;
    --kk-gold:      #D4AF37;
    --kk-radius:    14px;
    --kk-radius-lg: 20px;
    --nav-height:   72px;
}
*, *::before, *::after { box-sizing: border-box; }
body {
    font-family: 'Inter', sans-serif;
    font-size: 1rem;
    line-height: 1.7;
    color: #1A233A;
    background: #fff;
}
h1,h2,h3,h4,h5,h6,.font-heading { font-family:'Montserrat',sans-serif; font-weight:700; }
a { transition: color .2s, opacity .2s; }
img { max-width:100%; height:auto; }

/* Navbar */
.site-nav {
    position: fixed;
    top:0; left:0; right:0;
    height: var(--nav-height);
    z-index: 1050;
    transition: background .3s, box-shadow .3s;
    background: transparent;
    /* Transparent overlay bar must not steal clicks from hero content
       (e.g. breadcrumb links) beneath it — only its own controls do. */
    pointer-events: none;
}
.site-nav .container { pointer-events: none; }
.site-nav a,
.site-nav button,
.site-nav .btn,
.site-nav .navbar-brand,
.site-nav .navbar-toggler,
.site-nav .dropdown-menu { pointer-events: auto; }
.site-nav.scrolled {
    background: var(--kk-blue);
    box-shadow: 0 2px 16px rgba(0,0,0,.18);
}
/* Inner-page heroes sit full-bleed under the fixed navbar — push their content
   clear of it so the logo never overlaps the breadcrumb/title. (Home hero opts out.) */
main > section:first-child:not(.hero-home) {
    padding-top: calc(var(--nav-height) + 1.75rem) !important;
}
/* When scrolled the bar is solid, so it should capture clicks normally */
.site-nav.scrolled { pointer-events: auto; }
.site-nav .navbar-brand img { height:64px; width:auto; }
.site-nav .nav-link {
    color: rgba(255,255,255,.85) !important;
    font-size: .9rem;
    font-weight: 500;
    padding: .4rem .9rem !important;
    border-radius: 6px;
    transition: color .2s, background .2s;
}
.site-nav .nav-link:hover,
.site-nav .nav-link.active { color: var(--kk-gold) !important; }
.site-nav .navbar-toggler { border:none; color:rgba(255,255,255,.85); }
.site-nav .navbar-toggler:focus { box-shadow:none; }
@media (max-width:991.98px) {
    .site-nav { background: var(--kk-blue); }
    .site-nav .navbar-collapse { background:var(--kk-blue); padding:.75rem 1rem 1rem; }
}

/* Buttons */
.btn-kk, .btn-gold, .btn-amber {
    background: var(--kk-gold);
    color: var(--kk-blue);
    border: 2px solid var(--kk-gold);
    font-family: 'Montserrat', sans-serif;
    font-weight: 700;
    border-radius: 8px;
    padding: .55rem 1.5rem;
    transition: background .2s, color .2s, transform .15s;
}
.btn-kk:hover, .btn-gold:hover, .btn-amber:hover {
    background: #c09b28;
    border-color: #c09b28;
    color: var(--kk-blue);
    transform: translateY(-1px);
}
.btn-outline-white {
    background: transparent;
    color: #fff;
    border: 2px solid rgba(255,255,255,.5);
    font-family: 'Montserrat', sans-serif;
    font-weight: 600;
    border-radius: 8px;
    padding: .55rem 1.5rem;
    transition: background .2s, border-color .2s;
}
.btn-outline-white:hover { background:rgba(255,255,255,.12); border-color:#fff; color:#fff; }

/* Typography */
.display-hero {
    font-family: 'Montserrat', sans-serif;
    font-weight: 800;
    font-size: clamp(2.2rem,4.5vw,3.6rem);
    line-height: 1.15;
    letter-spacing: -.02em;
}
.section-title {
    font-family: 'Montserrat', sans-serif;
    font-weight: 800;
    font-size: clamp(1.65rem,2.8vw,2.4rem);
    color: var(--kk-blue);
    line-height: 1.25;
    margin-bottom: .5rem;
}
.section-title span { color: var(--kk-gold); }
.section-subtitle { font-size:1.05rem; color:#6B7280; max-width:580px; margin:0 auto; }
.eyebrow {
    display: inline-block;
    font-size: .75rem;
    font-weight: 700;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: var(--kk-gold);
    margin-bottom: .75rem;
}
.gold-bar { width:48px; height:4px; background:var(--kk-gold); border-radius:2px; margin-bottom:1.25rem; }
.stat-number {
    font-family: 'Montserrat', sans-serif;
    font-weight: 800;
    font-size: clamp(2rem,4vw,3rem);
    color: var(--kk-gold);
    line-height: 1;
}
.stat-label { font-size:.82rem; color:rgba(255,255,255,.6); text-transform:uppercase; letter-spacing:.06em; margin-top:.35rem; }

/* Layout */
.section-py { padding:80px 0; }
.bg-off-white { background:#F8F9FC; }
.bg-white { background:#fff; }

/* Cards */
.card-service {
    background: #fff;
    border: 1px solid #EAEEf5;
    border-radius: var(--kk-radius);
    transition: box-shadow .25s, transform .25s;
}
.card-service:hover { box-shadow:0 12px 36px rgba(15,30,67,.1); transform:translateY(-3px); }

/* Icon badge — circular container for Bootstrap Icons (used on hero + cards) */
.icon-circle {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(15,30,67,.08);
    color: var(--kk-blue);
    font-size: 1.5rem;
    line-height: 1;
    flex-shrink: 0;
}

/* Badges */
.badge-dept { background:rgba(15,30,67,.08); color:var(--kk-blue); font-size:.75rem; font-weight:600; }

/* Footer */
.site-footer { background:var(--kk-blue); color:rgba(255,255,255,.7); }
.site-footer h6 { color:#fff; font-family:'Montserrat',sans-serif; font-weight:700; font-size:.85rem; letter-spacing:.08em; text-transform:uppercase; margin-bottom:1rem; }
.site-footer a { color:rgba(255,255,255,.6); text-decoration:none; font-size:.9rem; display:block; margin-bottom:.5rem; transition:color .2s; }
.site-footer a:hover { color:var(--kk-gold); }
.footer-bottom { border-top:1px solid rgba(255,255,255,.1); padding:1.25rem 0; font-size:.82rem; color:rgba(255,255,255,.4); }

/* Floating WhatsApp button (bottom-right). */
.kk-wa-fab {
    position: fixed;
    right: 22px;
    bottom: 22px;
    z-index: 1040;
    width: 58px; height: 58px;
    border-radius: 50%;
    background: #25D366;
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 6px 20px rgba(37, 211, 102, .45);
    transition: transform .2s ease, box-shadow .2s ease;
}
.kk-wa-fab:hover,
.kk-wa-fab:focus-visible {
    color: #fff;
    transform: translateY(-2px) scale(1.04);
    box-shadow: 0 10px 26px rgba(37, 211, 102, .55);
}
.kk-wa-fab__pulse {
    position: absolute;
    inset: 0;
    border-radius: 50%;
    background: #25D366;
    z-index: -1;
    animation: kk-wa-pulse 2.2s ease-out infinite;
}
@keyframes kk-wa-pulse {
    0%   { transform: scale(1);   opacity: .55; }
    70%  { transform: scale(1.6); opacity: 0;  }
    100% { transform: scale(1.6); opacity: 0;  }
}
@media (prefers-reduced-motion: reduce) {
    .kk-wa-fab { transition: none; }
    .kk-wa-fab:hover, .kk-wa-fab:focus-visible { transform: none; }
    .kk-wa-fab__pulse { animation: none; opacity: 0; }
}
@media (max-width: 480px) {
    .kk-wa-fab { width: 52px; height: 52px; right: 16px; bottom: 16px; }
}
</style>

{{-- Organization structured data — site-wide. {{ request()->root() }} keeps it domain-agnostic. --}}
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "Organization",
    "name": "Kikosi Kazi Security",
    "url": "{{ request()->root() }}",
    "logo": "{{ asset('images/logo.png') }}",
    "description": "Tanzania's trusted integrated security and business support services partner — Security & Risk Management, Human Capital Solutions, Insurance Advisory & Brokerage, and Facility Management Services.",
    "email": "info@kikosikazi.co.tz",
    "telephone": "+255700000000",
    "address": {
        "@@type": "PostalAddress",
        "addressLocality": "Dar es Salaam",
        "addressCountry": "TZ"
    },
    "areaServed": "Tanzania",
    "sameAs": [
        "https://instagram.com/kikosikazisecurity",
        "https://facebook.com/kikosikazisecurity",
        "https://linkedin.com/company/kikosikazisecurity",
        "https://tiktok.com/@kikosikazisecurity",
        "https://x.com/kikosikazisecurity"
    ]
}
</script>

@stack('schema')
</head>
<body>

<!-- NAVBAR -->
<nav class="site-nav navbar navbar-expand-lg" id="siteNav">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Kikosi Kazi Security"
                 style="filter:brightness(0) invert(1)">
        </a>
        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#navMenu"
                aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
            <i class="bi bi-list fs-2"></i>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1 py-2 py-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('about') ? 'active' : '' }}"
                       href="{{ route('about') }}" role="button" data-bs-toggle="dropdown">About Us</a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li><a class="dropdown-item" href="{{ route('about', 'story') }}">Our Story</a></li>
                        <li><a class="dropdown-item" href="{{ route('about', 'mission') }}">Mission, Vision &amp; Core Values</a></li>
                        <li><a class="dropdown-item" href="{{ route('about', 'partner') }}">Why Partner With Us</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('services.*') ? 'active' : '' }}"
                       href="#" role="button" data-bs-toggle="dropdown">Services</a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li><a class="dropdown-item" href="{{ route('services.show', 'security') }}">Security &amp; Risk Management</a></li>
                        <li><a class="dropdown-item" href="{{ route('services.show', 'hr') }}">Human Capital Solutions</a></li>
                        <li><a class="dropdown-item" href="{{ route('services.show', 'insurance') }}">Insurance Advisory &amp; Brokerage</a></li>
                        <li><a class="dropdown-item" href="{{ route('services.show', 'cleaning') }}">Facility Management Services</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('careers.*') ? 'active' : '' }}" href="{{ route('careers.index') }}">Careers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('gallery') ? 'active' : '' }}" href="{{ route('gallery') }}">Gallery</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('clients') ? 'active' : '' }}" href="{{ route('clients') }}">Clients</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('contact.*') ? 'active' : '' }}" href="{{ route('contact.index') }}">Contact</a>
                </li>
            </ul>
            <div class="d-flex align-items-center gap-2 ms-lg-3 mt-3 mt-lg-0">
                @auth
                    @if(auth()->user()->hasRole('candidate'))
                    {{-- Candidate account menu --}}
                    <div class="dropdown">
                        <a class="btn btn-gold btn-sm px-3 dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>My Account
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('candidate.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ route('candidate.applications.index') }}"><i class="bi bi-file-earmark-text-fill me-2"></i>My Applications</a></li>
                            <li><a class="dropdown-item" href="{{ route('candidate.profile.index') }}"><i class="bi bi-person-fill me-2"></i>My Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @else
                    {{-- Admin / staff --}}
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-gold btn-sm px-3">
                        <i class="bi bi-speedometer2 me-1"></i>Admin
                    </a>
                    @endif
                @endauth
                {{-- Guests: no Sign In / Register here — candidate auth lives on the Careers page --}}
            </div>
        </div>
    </div>
</nav>

@if(session('success') || session('error'))
<div style="padding-top:var(--nav-height)">
    <div class="container pt-3">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
    </div>
</div>
@endif

<main>@yield('content')</main>

<!-- FOOTER -->
<footer class="site-footer pt-5 pb-0">
    <div class="container">
        <div class="row g-4 pb-5">
            <div class="col-lg-4">
                <img src="{{ asset('images/logo.png') }}" alt="Kikosi Kazi"
                     style="height:88px;width:auto;filter:brightness(0) invert(1);margin-bottom:1rem">
                <p style="font-size:.9rem;line-height:1.8;max-width:300px">
                    Tanzania's trusted integrated services partner — Security, HR, Insurance and Cleaning, all under one roof.
                </p>
                <div class="d-flex gap-2 mt-3 flex-wrap">
                    <a href="https://instagram.com/kikosikazisecurity" target="_blank" rel="noopener noreferrer" style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,.1);color:rgba(255,255,255,.7)" aria-label="Instagram — @kikosikazisecurity"><i class="bi bi-instagram"></i></a>
                    <a href="https://facebook.com/kikosikazisecurity" target="_blank" rel="noopener noreferrer" style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,.1);color:rgba(255,255,255,.7)" aria-label="Facebook — @kikosikazisecurity"><i class="bi bi-facebook"></i></a>
                    <a href="https://linkedin.com/company/kikosikazisecurity" target="_blank" rel="noopener noreferrer" style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,.1);color:rgba(255,255,255,.7)" aria-label="LinkedIn — @kikosikazisecurity"><i class="bi bi-linkedin"></i></a>
                    <a href="https://tiktok.com/@kikosikazisecurity" target="_blank" rel="noopener noreferrer" style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,.1);color:rgba(255,255,255,.7)" aria-label="TikTok — @kikosikazisecurity"><i class="bi bi-tiktok"></i></a>
                    <a href="https://x.com/kikosikazisecurity" target="_blank" rel="noopener noreferrer" style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,.1);color:rgba(255,255,255,.7)" aria-label="X — @kikosikazisecurity"><i class="bi bi-twitter-x"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-6">
                <h6>Company</h6>
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('about') }}">About Us</a>
                <a href="{{ route('careers.index') }}">Careers</a>
                <a href="{{ route('gallery') }}">Gallery</a>
                <a href="{{ route('clients') }}">Clients</a>
                <a href="{{ route('contact.index') }}">Contact</a>
            </div>
            <div class="col-lg-3 col-6">
                <h6>Services</h6>
                <a href="{{ route('services.show', 'security') }}">Security &amp; Risk Management</a>
                <a href="{{ route('services.show', 'hr') }}">Human Capital Solutions</a>
                <a href="{{ route('services.show', 'insurance') }}">Insurance Advisory &amp; Brokerage</a>
                <a href="{{ route('services.show', 'cleaning') }}">Facility Management Services</a>
            </div>
            <div class="col-lg-3">
                <h6>Contact Us</h6>
                <a href="tel:+255700000000"><i class="bi bi-telephone-fill me-2"></i>+255 700 000 000</a>
                <a href="mailto:info@kikosikazi.co.tz"><i class="bi bi-envelope-fill me-2"></i>info@kikosikazi.co.tz</a>
                <p style="font-size:.9rem;margin-top:.5rem"><i class="bi bi-geo-alt-fill me-2" style="color:var(--kk-gold)"></i>Dar es Salaam, Tanzania</p>
                <a href="{{ route('contact.index') }}" class="btn btn-gold btn-sm px-4 mt-2 d-inline-block">Get in Touch</a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container d-flex flex-wrap justify-content-between align-items-center gap-2">
            <span>&copy; {{ date('Y') }} Kikosi Kazi Security. All rights reserved.</span>
            <span>TSIA Registered &bull; PSCGP Compliant &bull; Tanzania</span>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function(){
    var nav = document.getElementById('siteNav');
    if (!nav) return;
    function onScroll(){ nav.classList.toggle('scrolled', window.scrollY > 40); }
    window.addEventListener('scroll', onScroll, {passive:true});
    onScroll();
})();

// ── Anchor scrolling that clears the fixed navbar (e.g. the About Us dropdown) ──
(function(){
    var OFFSET = 90; // navbar height + a little breathing room
    function scrollToHash(hash, smooth){
        var el; try { el = document.querySelector(hash); } catch(e){ return; }
        if (!el) return;
        var y = el.getBoundingClientRect().top + window.pageYOffset - OFFSET;
        window.scrollTo({ top: y, behavior: smooth ? 'smooth' : 'auto' });
    }
    // If the page opened with a hash, the browser's native jump ignores the fixed
    // navbar — re-align once the layout has settled.
    if (window.location.hash) {
        window.addEventListener('load', function(){ setTimeout(function(){ scrollToHash(window.location.hash, false); }, 120); });
    }
    // Intercept in-page anchor links so the target lands neatly below the navbar.
    document.addEventListener('click', function(e){
        var a = e.target.closest('a[href*="#"]');
        if (!a) return;
        var href = a.getAttribute('href') || '';
        var hash = href.slice(href.indexOf('#'));
        if (hash.length < 2) return;                 // bare "#" (e.g. dropdown toggles)
        var target; try { target = document.querySelector(hash); } catch(err){ return; }
        if (!target) return;                          // target not on this page → navigate normally
        e.preventDefault();
        history.replaceState(null, '', hash);
        scrollToHash(hash, true);
    });
})();
</script>

{{-- Floating WhatsApp button — uses the same placeholder number as the contact page. --}}
@php
    $waNumber   = '255700000000'; // TODO: replace with the real business WhatsApp number
    $waMessage  = rawurlencode('Hello Kikosi Kazi Security, I would like to enquire about your services.');
@endphp
<a href="https://wa.me/{{ $waNumber }}?text={{ $waMessage }}"
   class="kk-wa-fab"
   target="_blank" rel="noopener noreferrer"
   aria-label="Chat with Kikosi Kazi Security on WhatsApp">
    <svg viewBox="0 0 32 32" width="30" height="30" aria-hidden="true" fill="currentColor">
        <path d="M16.001 3C9.373 3 4 8.373 4 15.001c0 2.118.553 4.188 1.603 6.018L4 29l8.184-1.561a11.93 11.93 0 0 0 3.817.626h.001C22.628 28.065 28 22.69 28 16.065 28 9.437 22.628 3 16.001 3zm0 21.82h-.001a9.86 9.86 0 0 1-3.522-.65l-.252-.101-4.86.928.98-4.74-.164-.244a9.84 9.84 0 0 1-1.51-5.25c0-5.46 4.45-9.91 9.91-9.91 2.65 0 5.14 1.04 7.01 2.91a9.86 9.86 0 0 1 2.9 7.01c0 5.46-4.45 9.91-9.91 9.91zm5.44-7.42c-.3-.15-1.77-.87-2.04-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.26-.46-2.4-1.48-.89-.79-1.49-1.77-1.66-2.07-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.07-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51l-.57-.01c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.49 0 1.47 1.07 2.89 1.22 3.09.15.2 2.1 3.2 5.08 4.49.71.31 1.26.49 1.69.63.71.23 1.36.2 1.87.12.57-.09 1.77-.72 2.02-1.42.25-.7.25-1.3.17-1.42-.07-.13-.27-.2-.57-.35z"/>
    </svg>
    <span class="kk-wa-fab__pulse" aria-hidden="true"></span>
</a>
@stack('scripts')
</body>
</html>
