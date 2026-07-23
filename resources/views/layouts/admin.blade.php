<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel — Kikosi Kazi')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc4/dist/css/adminlte.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    @stack('styles')
    <style>
        :root { --kk-blue: #0D47A1; --kk-amber: #FFB300; }
        body { font-family: 'Poppins', sans-serif; }
        .sidebar-brand-text span { color: var(--kk-amber); }
        .nav-icon { margin-right: .5rem; }

        /* ── Toast Notifications ─────────────────────────── */
        .kk-toast-wrap {
            position: fixed;
            top: 72px;
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
        .kk-toast-title { font-weight: 700; font-size: .78rem; text-transform: uppercase; letter-spacing: .07em; opacity: .8; margin-bottom: 3px; }
        .kk-toast-msg { font-size: .9rem; line-height: 1.45; }
        .kk-toast-close { background: none; border: none; color: rgba(255,255,255,.7); font-size: 1.3rem; line-height: 1; cursor: pointer; padding: 0; flex-shrink: 0; }
        .kk-toast-close:hover { color: #fff; }
        .kk-toast-bar { position: absolute; bottom: 0; left: 0; height: 4px; border-radius: 0 0 12px 12px; background: rgba(255,255,255,.45); animation: kkShrink 5s linear forwards; }
        @keyframes kkSlideIn  { from { opacity:0; transform:translateX(110%); } to { opacity:1; transform:translateX(0); } }
        @keyframes kkSlideOut { from { opacity:1; transform:translateX(0); } to { opacity:0; transform:translateX(110%); } }
        @keyframes kkShrink   { from { width:100%; } to { width:0%; } }
    </style>
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">

<div class="app-wrapper">

    <!-- TOP NAVBAR -->
    <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                        <i class="bi bi-list fs-4"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-md-block">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link fw-bold" style="color:var(--kk-blue)">
                        KK Admin
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name ?? 'Admin' }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('home') }}" target="_blank">
                            <i class="bi bi-globe me-2"></i>View Website
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <!-- SIDEBAR -->
    <aside class="app-sidebar bg-dark sidebar-dark-primary shadow">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}" class="brand-link text-decoration-none d-flex align-items-center px-3 py-3">
                <img src="{{ asset('images/logo.png') }}" alt="Kikosi Kazi"
                     style="height:34px;width:auto;display:block;filter:brightness(0) invert(1)">
            </a>
        </div>
        <div class="sidebar-wrapper">
            <nav class="mt-2">
                <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2 nav-icon"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.jobs.index') }}" class="nav-link {{ request()->routeIs('admin.jobs.*') ? 'active' : '' }}">
                            <i class="bi bi-briefcase-fill nav-icon"></i>
                            <p>Job Listings</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.applicants.index') }}" class="nav-link {{ request()->routeIs('admin.applicants.*') ? 'active' : '' }}">
                            <i class="bi bi-people-fill nav-icon"></i>
                            <p>Applicants</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.cms.index') }}" class="nav-link {{ request()->routeIs('admin.cms.*') ? 'active' : '' }}">
                            <i class="bi bi-pencil-square nav-icon"></i>
                            <p>CMS</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <hr class="border-secondary my-2">
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link" target="_blank">
                            <i class="bi bi-globe nav-icon"></i>
                            <p>View Website</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

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
            <div class="kk-toast-bar"></div>
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
            <div class="kk-toast-bar"></div>
        </div>
        @endif
    </div>
    @endif

    <!-- MAIN CONTENT -->
    <main class="app-main">
        <div class="app-content-header py-3 px-4">
            <div class="container-fluid">
                @yield('breadcrumb')
            </div>
        </div>
        <div class="app-content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="app-footer">
        <div class="float-end d-none d-sm-inline">Kikosi Kazi Admin</div>
        &copy; {{ date('Y') }} Kikosi Kazi Security. All rights reserved.
    </footer>

</div><!-- /.app-wrapper -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc4/dist/js/adminlte.min.js"></script>
<script>
function kkDismissToast(id) {
    var el = document.getElementById(id);
    if (el) { el.style.animation = 'kkSlideOut .35s ease forwards'; setTimeout(function(){ el.remove(); }, 350); }
}
document.addEventListener('DOMContentLoaded', function () {
    ['kkToastSuccess','kkToastError'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) setTimeout(function(){ kkDismissToast(id); }, 5000);
    });
});
</script>
@s