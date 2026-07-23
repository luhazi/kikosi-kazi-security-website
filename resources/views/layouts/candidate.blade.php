<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Candidate Portal — Kikosi Kazi')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @stack('styles')
    <style>
        :root {
            --kk-blue:   #0F1E43;
            --kk-gold:   #D4AF37;
            --kk-amber:  #D4AF37;  /* alias for legacy references */
            --sidebar-width: 250px;
        }
        body {
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            line-height: 1.7;
            background: #F1F4F9;
            color: #1A233A;
        }
        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
        }

        /* ── Sidebar ─────────────────────────────────────────── */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--kk-blue);
            z-index: 1040;
            display: flex;
            flex-direction: column;
            transition: transform .3s ease;
            overflow-y: auto;
        }
        .sidebar-brand {
            padding: 1.4rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,.1);
            color: #fff;
            font-family: 'Montserrat', sans-serif;
            font-weight: 800;
            font-size: 1.25rem;
            text-decoration: none;
            display: block;
            letter-spacing: -.01em;
        }
        .sidebar-brand span { color: var(--kk-gold); }
        .sidebar-section-label {
            padding: 1.2rem 1.5rem .4rem;
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: rgba(255,255,255,.35);
        }
        .sidebar-nav { list-style: none; padding: .5rem 0; margin: 0; }
        .sidebar-nav .nav-item { margin: 1px 0; }
        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: .8rem;
            padding: .65rem 1.5rem;
            color: rgba(255,255,255,.7);
            text-decoration: none;
            font-size: .88rem;
            font-weight: 500;
            border-radius: 0;
            transition: background .15s, color .15s;
        }
        .sidebar-nav .nav-link:hover { background: rgba(255,255,255,.07); color: #fff; }
        .sidebar-nav .nav-link.active {
            background: rgba(255,255,255,.12);
            color: #fff;
            border-left: 3px solid var(--kk-gold);
            font-weight: 600;
        }
        .sidebar-nav .nav-link i { font-size: 1.05rem; width: 20px; text-align: center; flex-shrink:0; }

        /* ── Top Navbar ──────────────────────────────────────── */
        .top-navbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: 64px;
            background: #fff;
            border-bottom: 1px solid #E8ECF4;
            z-index: 1030;
            display: flex;
            align-items: center;
            padding: 0 2rem;
            gap: 1rem;
            box-shadow: 0 1px 4px rgba(0,0,0,.04);
        }
        .top-navbar .navbar-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 1.05rem;
            color: var(--kk-blue);
            flex: 1;
        }

        /* ── Main Content ────────────────────────────────────── */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: 64px;
            padding: 2rem 2.5rem;
            min-height: calc(100vh - 64px - 48px);
        }

        /* ── Footer bar ──────────────────────────────────────── */
        .portal-footer {
            margin-left: var(--sidebar-width);
            background: #fff;
            border-top: 1px solid #E8ECF4;
            padding: .75rem 2.5rem;
            font-size: .8rem;
            color: #8A94A8;
            text-align: center;
        }

        /* Sidebar overlay for mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1039;
        }

        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .sidebar-overlay.show { display: block; }
            .top-navbar { left: 0; }
            .main-content { margin-left: 0; }
            .portal-footer { margin-left: 0; }
        }

        /* ── Toast Notifications ─────────────────────────── */
        .kk-toast-wrap {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-width: 360px;
            width: calc(100vw - 40px);
        }
        .kk-toast {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 16px 16px 22px;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,.18);
            position: relative;
            overflow: hidden;
            animation: kkSlideIn .4s cubic-bezier(.22,.61,.36,1) forwards;
            color: #fff;
        }
        .kk-toast-success { background: #1B5E20; }
        .kk-toast-error   { background: #B71C1C; }
        .kk-toast-icon { font-size: 1.4rem; flex-shrink: 0; margin-top: 1px; }
        .kk-toast-body { flex: 1; }
        .kk-toast-title { font-family: 'Montserrat', sans-serif; font-weight: 700; font-size: .82rem; text-transform: uppercase; letter-spacing: .06em; opacity: .85; margin-bottom: 3px; }
        .kk-toast-msg { font-size: .92rem; line-height: 1.45; }
        .kk-toast-close { background: none; border: none; color: rgba(255,255,255,.7); font-size: 1.3rem; line-height: 1; cursor: pointer; padding: 0; flex-shrink: 0; transition: color .15s; }
        .kk-toast-close:hover { color: #fff; }
        .kk-toast-bar { position: absolute; bottom: 0; left: 0; height: 4px; border-radius: 0 0 12px 12px; animation: kkShrink 5s linear forwards; }
        .kk-toast-bar-success { background: rgba(255,255,255,.5); }
        .kk-toast-bar-error   { background: rgba(255,255,255,.5); }
        @keyframes kkSlideIn {
            from { opacity: 0; transform: translateX(110%); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes kkSlideOut {
            from { opacity: 1; transform: translateX(0); }
            to   { opacity: 0; transform: translateX(110%); }
        }
        @keyframes kkShrink {
            from { width: 100%; }
            to   { width: 0%; }
        }
    </style>
</head>
<body>

<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
    <a class="sidebar-brand" href="{{ route('candidate.dashboard') }}">
        <img src="{{ asset('images/logo.png') }}" alt="Kikosi Kazi"
             style="height:36px;width:auto;display:block;filter:brightness(0) invert(1)">
    </a>

    {{-- User badge --}}
    <div style="padding:.9rem 1.5rem;border-bottom:1px solid rgba(255,255,255,.08)">
        <div style="display:flex;align-items:center;gap:.65rem">
            <div style="width:34px;height:34px;border-radius:50%;background:var(--kk-gold);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="bi bi-person-fill" style="color:#000;font-size:.9rem"></i>
            </div>
            <div style="overflow:hidden">
                <div style="color:#fff;font-size:.82rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                    {{ Str::words(Auth::user()->name ?? 'Candidate', 2, '') }}
                </div>
                <div style="color:rgba(255,255,255,.45);font-size:.7rem">Candidate</div>
            </div>
        </div>
    </div>

    <div class="sidebar-section-label">Main Menu</div>
    <ul class="sidebar-nav">
        <li class="nav-item">
            <a href="{{ route('candidate.dashboard') }}"
               class="nav-link {{ request()->routeIs('candidate.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('candidate.profile.index') }}"
               class="nav-link {{ request()->routeIs('candidate.profile.*') ? 'active' : '' }}">
                <i class="bi bi-person-circle"></i> My Profile
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('candidate.documents.index') }}"
               class="nav-link {{ request()->routeIs('candidate.documents.*') ? 'active' : '' }}">
                <i class="bi bi-folder2-open"></i> My Documents
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('candidate.applications.index') }}"
               class="nav-link {{ request()->routeIs('candidate.applications.*') ? 'active' : '' }}">
                <i class="bi bi-send-check-fill"></i> My Applications
            </a>
        </li>
        @php $profile = Auth::user()->candidateProfile; @endphp
        @if($profile && $profile->completeness_pct >= 100)
        <li class="nav-item">
            <a href="{{ route('candidate.cv.show') }}" target="_blank"
               class="nav-link">
                <i class="bi bi-file-earmark-pdf-fill"></i> Download CV
            </a>
        </li>
        @endif
    </ul>

    <div class="sidebar-section-label">Site</div>
    <ul class="sidebar-nav">
        <li class="nav-item">
            <a href="{{ route('careers.index') }}" class="nav-link">
                <i class="bi bi-briefcase-fill"></i> Browse Jobs
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('home') }}" class="nav-link">
                <i class="bi bi-globe"></i> Public Site
            </a>
        </li>
    </ul>

    <div class="mt-auto" style="padding:1rem 1.25rem;border-top:1px solid rgba(255,255,255,.08)">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-sm w-100 fw-semibold"
                    style="background:rgba(255,255,255,.1);color:rgba(255,255,255,.8);border:1px solid rgba(255,255,255,.15)">
                <i class="bi bi-box-arrow-right me-2"></i>Logout
            </button>
        </form>
    </div>
</aside>

<!-- TOP NAVBAR -->
<div class="top-navbar">
    <button class="btn btn-sm d-lg-none me-1" style="background:transparent;border:none;color:var(--kk-blue)" onclick="toggleSidebar()">
        <i class="bi bi-list fs-3"></i>
    </button>
    <span class="navbar-title">@yield('page-title', 'Candidate Portal')</span>
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('careers.index') }}" class="btn btn-sm fw-semibold d-none d-md-inline-flex align-items-center gap-1"
           style="background:var(--kk-blue);color:#fff;border-radius:8px">
            <i class="bi bi-briefcase-fill"></i> Browse Jobs
        </a>
        <div style="width:1px;height:28px;background:#E8ECF4" class="d-none d-md-block"></div>
        <div class="d-flex align-items-center gap-2">
            <div style="width:34px;height:34px;border-radius:50%;background:var(--kk-blue);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="bi bi-person-fill text-white" style="font-size:.85rem"></i>
            </div>
            <div class="d-none d-md-block">
                <div style="font-size:.82rem;font-weight:600;color:var(--kk-blue);line-height:1.2">{{ Str::words(Auth::user()->name ?? 'Candidate', 2, '') }}</div>
                <div style="font-size:.7rem;color:#8A94A8">Candidate</div>
            </div>
        </div>
    </div>
</div>

<!-- TOAST NOTIFICATIONS -->
@if(session('success') || session('error'))
<div class="kk-toast-wrap" id="kkToastWrap">
    @if(session('success'))
    <div class="kk-toast kk-toast-success" id="kkToastSuccess">
        <div class="kk-toast-icon"><i class="bi bi-check-circle-fill"></i></div>
        <div class="kk-toast-body">
            <div class="kk-toast-title">Success</div>
            <div class="kk-toast-msg">{{ session('success') }}</div>
        </div>
        <button class="kk-toast-close" onclick="kkDismissToast('kkToastSuccess')">&times;</button>
        <div class="kk-toast-bar kk-toast-bar-success"></div>
    </div>
    @endif
    @if(session('error'))
    <div class="kk-toast kk-toast-error" id="kkToastError">
        <div class="kk-toast-icon"><i class="bi bi-exclamation-circle-fill"></i></div>
        <div class="kk-toast-body">
            <div class="kk-toast-title">Error</div>
            <div class="kk-toast-msg">{{ session('error') }}</div>
        </div>
        <button class="kk-toast-close" onclick="kkDismissToast('kkToastError')">&times;</button>
        <div class="kk-toast-bar kk-toast-bar-error"></div>
    </div>
    @endif
</div>
@endif

<!-- MAIN CONTENT -->
<main class="main-content">
    @yield('content')
</main>

<!-- FOOTER BAR -->
<footer class="portal-footer">
    &copy; {{ date('Y') }} Kikosi Kazi Security. All rights reserved.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('show');
        document.getElementById('sidebarOverlay').classList.toggle('show');
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('show');
        document.getElementById('sidebarOverlay').classList.remove('show');
    }
    function kkDismissToast(id) {
        var el = document.getElementById(id);
        if (el) { el.style.animation = 'kkSlideOut .35s ease forwards'; setTimeout(function(){ el.remove(); }, 350); }
    }
    document.addEventListener('DOMContentLoaded', function () {
        ['kkToastSuccess','kkToastError'].forEach(function(id) {
            var el = document.getElementById(id);
            if (!el) return;
            // auto-dismiss after 5s
            setTimeout(function(){ kkDismissToast(id); }, 5000);
        });
    });
</script>
@stack('scripts')
</body>
</html>
