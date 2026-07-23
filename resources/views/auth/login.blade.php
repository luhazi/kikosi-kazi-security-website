<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign In — Kikosi Kazi</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800&family=Inter:wght@400;600&display=swap" rel="stylesheet">
<style>
:root{--kk-blue:#0F1E43;--kk-purple:#3B1F6E;--kk-gold:#D4AF37}
body{font-family:'Inter',sans-serif;background:#f0eef8;min-height:100vh;display:flex;align-items:center}
.auth-card{border:none;border-radius:20px;box-shadow:0 20px 56px rgba(59,31,110,.18);overflow:hidden}
.card-brand{background:linear-gradient(135deg,var(--kk-blue) 0%,var(--kk-purple) 100%);padding:2rem 2.5rem;text-align:center;border-bottom:4px solid var(--kk-gold)}
.card-brand img{height:60px;width:auto}
.btn-kk{background:var(--kk-gold);color:var(--kk-blue);border:none;font-weight:700;padding:.7rem 2rem;border-radius:8px;font-family:'Montserrat',sans-serif}
.btn-kk:hover{background:#c09b28;color:var(--kk-blue)}
.form-control:focus{border-color:var(--kk-gold);box-shadow:0 0 0 .2rem rgba(212,175,55,.2)}
.form-label{font-weight:600;font-size:.875rem;color:#374151}
a.gold{color:var(--kk-gold);text-decoration:none}
a.gold:hover{color:#c09b28}
</style>
</head>
<body>
<div class="container py-5">
<div class="row justify-content-center">
<div class="col-md-5 col-lg-4">
<div class="card auth-card">
<div class="card-brand">
<a href="{{ route('home') }}" style="display:inline-block;background:#fff;border-radius:14px;padding:10px 18px;box-shadow:0 2px 12px rgba(0,0,0,.18)">
<img src="{{ asset('images/logo.png') }}" alt="Kikosi Kazi Security">
</a>
<p class="text-white mb-0 mt-2 small fw-semibold" style="letter-spacing:1.5px;opacity:.85">{{ ($admin ?? false) ? 'KIKOSI KAZI ADMIN PANEL' : 'APPLICANT LOGIN' }}</p>
</div>
<div class="card-body p-4 p-md-5">
<h5 class="fw-bold mb-1">{{ ($admin ?? false) ? 'Staff Sign In' : 'Welcome back' }}</h5>
<p class="text-muted small mb-4">{{ ($admin ?? false) ? 'Admin, HR & recruitment access only' : 'Sign in to your candidate account' }}</p>
@if(session('status'))<div class="alert alert-success py-2 small">{{ session('status') }}</div>@endif
@if($errors->any())
<div class="alert alert-danger py-2 small"><ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif
<form method="POST" action="{{ route('login') }}">
@csrf
<input type="hidden" name="portal" value="{{ ($admin ?? false) ? 'admin' : 'candidate' }}">
<div class="mb-3">
<label for="email" class="form-label">Email Address</label>
<div class="input-group">
<span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope-fill text-muted"></i></span>
<input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" placeholder="you@example.com" required autofocus autocomplete="username">
@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
</div>
<div class="mb-3">
<div class="d-flex justify-content-between">
<label for="password" class="form-label">Password</label>
@if(Route::has('password.request'))<a href="{{ route('password.request') }}" class="small gold">Forgot password?</a>@endif
</div>
<div class="input-group">
<span class="input-group-text bg-light border-end-0"><i class="bi bi-lock-fill text-muted"></i></span>
<input id="password" type="password" name="password" class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror" placeholder="Your password" required autocomplete="current-password">
@error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
</div>
<div class="mb-4">
<div class="form-check">
<input class="form-check-input" type="checkbox" name="remember" id="remember">
<label class="form-check-label small text-muted" for="remember">Remember me</label>
</div>
</div>
<div class="d-grid mb-3">
<button type="submit" class="btn btn-kk"><i class="bi bi-box-arrow-in-right me-2"></i>Sign In</button>
</div>
@unless($admin ?? false)
<p class="text-center small text-muted mb-0">Don't have an account? <a href="{{ route('register') }}" class="gold fw-semibold">Create one</a></p>
@endunless
</form>
</div>
</div>
<p class="text-center small mt-3"><a href="{{ route('home') }}" class="text-muted text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Back to website</a></p>
</div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
