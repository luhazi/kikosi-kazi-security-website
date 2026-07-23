<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Account — Kikosi Kazi</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800&family=Inter:wght@400;600&display=swap" rel="stylesheet">
<style>
:root{--kk-blue:#0F1E43;--kk-purple:#3B1F6E;--kk-gold:#D4AF37}
body{font-family:'Inter',sans-serif;background:#f0eef8;min-height:100vh;display:flex;align-items:center}
.auth-card{border:none;border-radius:20px;box-shadow:0 20px 56px rgba(59,31,110,.18);overflow:hidden}
.card-brand{background:linear-gradient(135deg,var(--kk-blue) 0%,var(--kk-purple) 100%);padding:1.75rem 2.5rem;text-align:center;border-bottom:4px solid var(--kk-gold)}
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
<div class="col-md-6 col-lg-5">
<div class="card auth-card">
<div class="card-brand">
<a href="{{ route('home') }}" style="display:inline-block;background:#fff;border-radius:14px;padding:10px 18px;box-shadow:0 2px 12px rgba(0,0,0,.18)">
<img src="{{ asset('images/logo.png') }}" alt="Kikosi Kazi Security" style="height:52px;width:auto">
</a>
<p class="text-white mb-0 mt-2 small fw-semibold" style="letter-spacing:1.5px;opacity:.85">APPLICANT PORTAL</p>
</div>
<div class="card-body p-4 p-md-5">
<h5 class="fw-bold mb-1">Create your account</h5>
<p class="text-muted small mb-4">Join Kikosi Kazi and apply for opportunities</p>

@if($errors->any())
<div class="alert alert-danger py-2 small">
<ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

<form method="POST" action="{{ route('register') }}">
@csrf
<div class="mb-3">
<label for="name" class="form-label">Full Name</label>
<div class="input-group">
<span class="input-group-text bg-light border-end-0"><i class="bi bi-person-fill text-muted"></i></span>
<input id="name" type="text" name="name" value="{{ old('name') }}"
       class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror"
       placeholder="e.g. John Doe" required autofocus autocomplete="name">
@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
</div>

<div class="mb-3">
<label for="email" class="form-label">Email Address</label>
<div class="input-group">
<span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope-fill text-muted"></i></span>
<input id="email" type="email" name="email" value="{{ old('email') }}"
       class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
       placeholder="you@example.com" required autocomplete="username">
@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
</div>

<div class="mb-3">
<label for="password" class="form-label">Password</label>
<div class="input-group">
<span class="input-group-text bg-light border-end-0"><i class="bi bi-lock-fill text-muted"></i></span>
<input id="password" type="password" name="password"
       class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror"
       placeholder="Min. 8 characters" required autocomplete="new-password">
@error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
</div>

<div class="mb-4">
<label for="password_confirmation" class="form-label">Confirm Password</label>
<div class="input-group">
<span class="input-group-text bg-light border-end-0"><i class="bi bi-lock-fill text-muted"></i></span>
<input id="password_confirmation" type="password" name="password_confirmation"
       class="form-control border-start-0 ps-0"
       placeholder="Re-enter password" required autocomplete="new-password">
</div>
</div>

<div class="mb-4">
<div class="form-check">
<input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox"
       name="terms" id="terms" value="1" {{ old('terms') ? 'checked' : '' }} required>
<label class="form-check-label small text-muted" for="terms">
I confirm that all the information I provide is <strong>correct and true</strong>, and I agree to Kikosi Kazi's
<strong>Terms &amp; Conditions</strong> — I understand that submitting false or misleading details may lead to my
application being rejected.
</label>
@error('terms')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
</div>
</div>

<div class="d-grid mb-3">
<button type="submit" class="btn btn-kk">
<i class="bi bi-person-plus-fill me-2"></i>Create Account
</button>
</div>

<p class="text-center small text-muted mb-0">
Already have an account? <a href="{{ route('login') }}" class="gold fw-semibold">Sign in</a>
</p>
</form>
</div>
</div>
<p class="text-center small mt-3">
<a href="{{ route('home') }}" class="text-muted text-decoration-none">
<i class="bi bi-arrow-left me-1"></i>Back to website
</a>
</p>
</div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
