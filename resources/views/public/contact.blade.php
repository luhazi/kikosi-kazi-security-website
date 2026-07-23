@extends('layouts.public')
@section('title', 'Contact Us — Kikosi Kazi Security')
@section('meta_description', 'Contact Kikosi Kazi Security for a free consultation on security, HR, insurance or facility management services in Tanzania. Call +255 700 000 000 or send us a message today.')
@section('og_title', 'Contact Kikosi Kazi Security — Request a Free Consultation')
@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "ContactPage",
    "name": "Contact Kikosi Kazi Security",
    "url": "{{ request()->url() }}",
    "mainEntity": {
        "@@type": "Organization",
        "name": "Kikosi Kazi Security",
        "telephone": "+255700000000",
        "email": "info@kikosikazi.co.tz",
        "address": { "@@type": "PostalAddress", "addressLocality": "Dar es Salaam", "addressCountry": "TZ" }
    }
}
</script>
@endpush
@section('navbar-class', 'solid')

@section('content')

{{-- HERO --}}
<section class="py-5" style="background:linear-gradient(135deg,#0D47A1 0%,#1565C0 100%);min-height:240px;display:flex;align-items:center">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active text-white">Contact Us</li>
            </ol>
        </nav>
        <h1 class="display-5 fw-bold text-white mb-2">Contact Us</h1>
        <p class="lead text-white-50">We are here to help. Reach out to our team today.</p>
    </div>
</section>

<div class="container section-py">
    <div class="row g-5">

        {{-- LEFT: Contact Form --}}
        <div class="col-lg-7">
            <h3 class="fw-bold mb-4" style="color:var(--kk-blue)">Send Us a Message</h3>

            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('contact.store') }}" method="POST" novalidate>
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="John Doe" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="you@example.com" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Phone Number <span class="text-muted fw-normal">(optional)</span></label>
                        <input type="tel" name="phone" value="{{ old('phone') }}"
                               class="form-control @error('phone') is-invalid @enderror"
                               placeholder="+255 700 000 000">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
                        <input type="text" name="subject" value="{{ old('subject') }}"
                               class="form-control @error('subject') is-invalid @enderror"
                               placeholder="How can we help?" required>
                        @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
                        <textarea name="message" rows="6"
                                  class="form-control @error('message') is-invalid @enderror"
                                  placeholder="Write your message here..." required>{{ old('message') }}</textarea>
                        @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-kk btn-lg px-5 fw-semibold">
                            <i class="bi bi-send-fill me-2"></i>Send Message
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- RIGHT: Contact Info --}}
        <div class="col-lg-5">
            <h3 class="fw-bold mb-4" style="color:var(--kk-blue)">Get In Touch</h3>

            <div class="card border-0 shadow-sm rounded-4 mb-4" style="border-left:4px solid var(--kk-blue) !important">
                <div class="card-body p-4">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex align-items-start gap-3 mb-4">
                            <i class="bi bi-geo-alt-fill fs-4 mt-1" style="color:var(--kk-blue)"></i>
                            <div>
                                <div class="fw-semibold mb-1">Office Address</div>
                                <div class="text-muted small">Kikosi Kazi Security<br>Plot No. XX, [Street Name]<br>Dar es Salaam, Tanzania</div>
                            </div>
                        </li>
                        <li class="d-flex align-items-start gap-3 mb-4">
                            <i class="bi bi-telephone-fill fs-4 mt-1" style="color:var(--kk-blue)"></i>
                            <div>
                                <div class="fw-semibold mb-1">Phone</div>
                                <a href="tel:+255700000000" class="text-muted small text-decoration-none">+255 700 000 000</a>
                            </div>
                        </li>
                        <li class="d-flex align-items-start gap-3 mb-4">
                            <i class="bi bi-envelope-fill fs-4 mt-1" style="color:var(--kk-blue)"></i>
                            <div>
                                <div class="fw-semibold mb-1">Email</div>
                                <a href="mailto:info@kikosikazi.co.tz" class="text-muted small text-decoration-none">info@kikosikazi.co.tz</a>
                            </div>
                        </li>
                        <li class="d-flex align-items-start gap-3 mb-4">
                            <i class="bi bi-whatsapp fs-4 mt-1" style="color:#25D366"></i>
                            <div>
                                <div class="fw-semibold mb-1">WhatsApp</div>
                                <a href="https://wa.me/255700000000" target="_blank" class="text-muted small text-decoration-none">+255 700 000 000</a>
                            </div>
                        </li>
                        <li class="d-flex align-items-start gap-3">
                            <i class="bi bi-clock-fill fs-4 mt-1" style="color:var(--kk-blue)"></i>
                            <div>
                                <div class="fw-semibold mb-1">Office Hours</div>
                                <div class="text-muted small">
                                    Monday – Friday: 8:00am – 6:00pm<br>
                                    Saturday: 9:00am – 1:00pm<br>
                                    Sunday &amp; Public Holidays: Closed<br>
                                    <em>(Security operations: 24/7)</em>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Map Placeholder --}}
            <div class="rounded-3 d-flex align-items-center justify-content-center text-muted"
                 style="height:250px;background:rgba(0,0,0,0.08);border-radius:8px">
                <div class="text-center">
                    <i class="bi bi-map-fill fs-1 d-block mb-2 opacity-50"></i>
                    <span class="small">Google Maps — Add embed code here</span>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
