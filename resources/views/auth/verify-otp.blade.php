<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verify Your Email — Kikosi Kazi</title>
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
.otp-input{
    letter-spacing:12px;
    font-size:2rem;
    font-weight:700;
    text-align:center;
    font-family:monospace;
    border:2px solid #e5e7eb;
    border-radius:12px;
    padding:.75rem;
    color:var(--kk-blue);
    width:100%;
    transition:border-color .2s;
}
.otp-input:focus{border-color:var(--kk-gold);box-shadow:0 0 0 .2rem rgba(212,175,55,.2);outline:none}
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
<img src="{{ asset('images/logo.png') }}" alt="Kikosi Kazi Security" style="height:52px;width:auto">
</a>
<p class="text-white mb-0 mt-2 small fw-semibold" style="letter-spacing:1.5px;opacity:.85">EMAIL VERIFICATION</p>
</div>
<div class="card-body p-4 p-md-5">

<div class="text-center mb-4">
<div style="width:64px;height:64px;background:rgba(212,175,55,.12);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
<i class="bi bi-envelope-check-fill" style="font-size:1.8rem;color:var(--kk-gold)"></i>
</div>
<h5 class="fw-bold mb-1">Check your email</h5>
<p class="text-muted small mb-0">We sent a 6-digit verification code to your email address. Enter it below to activate your account.</p>
</div>

@if(session('status'))
<div class="alert alert-success py-2 small text-center">
<i class="bi bi-check-circle-fill me-1"></i>{{ session('status') }}
</div>
@endif

@if($errors->has('code'))
<div class="alert alert-danger py-2 small">
<i class="bi bi-exclamation-circle-fill me-1"></i>{{ $errors->first('code') }}
</div>
@endif

<form method="POST" action="{{ route('otp.verify') }}">
@csrf
<div class="mb-4">
<input type="text"
       name="code"
       class="otp-input"
       maxlength="6"
       placeholder="— — — — — —"
       autofocus
       autocomplete="one-time-code"
       inputmode="numeric"
       pattern="\d{6}">
<div class="text-center mt-2">
<small class="text-muted">Enter the 6-digit code</small>
</div>
</div>

<div class="d-grid mb-4">
<button type="submit" class="btn btn-kk">
<i class="bi bi-shield-fill-check me-2"></i>Verify & Activate Account
</button>
</div>
</form>

<div class="text-center border-top pt-4">
<p class="small text-muted mb-2">Didn't receive the code?</p>
<form method="POST" action="{{ route('otp.resend') }}">
@csrf
<button type="submit" class="btn btn-sm btn-outline-secondary px-4">
<i class="bi bi-arrow-clockwise me-1"></i>Resend Code
</button>
</form>
</div>

</div>
</div>

<p class="text-center small mt-3">
<a href="{{ route('login') }}" class="text-muted text-decoration-none">
<i class="bi bi-arrow-left me-1"></i>Back to Sign In
</a>
</p>
</div>
</div>
</div>

<script>
// Auto-format: only allow digits, auto-submit on 6 digits
document.querySelector('.otp-input').addEventListener('input', function(e) {
    this.value = this.value.replace(/\D/g, '').slice(0, 6);
    if (this.value.length === 6) {
        this.closest('form').submit();
    }
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
