@extends('layouts.candidate')
@section('title', 'Apply for ' . $job->title)
@section('page-title', 'Apply for Job')

@section('content')

{{-- BREADCRUMB --}}
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('candidate.dashboard') }}" class="text-decoration-none">Portal</a></li>
        <li class="breadcrumb-item"><a href="{{ route('careers.index') }}" class="text-decoration-none">Jobs</a></li>
        <li class="breadcrumb-item active">Apply</li>
    </ol>
</nav>

<div class="row g-4">
    {{-- LEFT: Job Summary + Form --}}
    <div class="col-lg-8">

        {{-- Job Summary Card --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4" style="border-left:4px solid var(--kk-blue) !important">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-1" style="color:var(--kk-blue)">{{ $job->title }}</h5>
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge badge-dept px-2 py-1">{{ $job->department }}</span>
                    <span class="badge bg-light text-dark border px-2 py-1">
                        <i class="bi bi-geo-alt-fill me-1"></i>{{ $job->location }}
                    </span>
                    <span class="badge bg-light text-dark border px-2 py-1 {{ $job->deadline->isPast() ? 'text-danger' : '' }}">
                        <i class="bi bi-calendar3-fill me-1"></i>Deadline: {{ $job->deadline->format('d M Y') }}
                    </span>
                </div>
                <a href="{{ route('careers.show', $job) }}" class="text-decoration-none small fw-semibold" style="color:var(--kk-blue)">
                    <i class="bi bi-arrow-up-right-square me-1"></i>Read full job description
                </a>
            </div>
        </div>

        {{-- Application Form --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0" style="color:var(--kk-blue)">Your Application</h5>
            </div>
            <div class="card-body p-4">

                @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('candidate.applications.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="job_id" value="{{ $job->id }}">

                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            Cover Letter <span class="text-danger">*</span>
                            <span class="text-muted fw-normal small">(optional)</span>
                        </label>
                        <textarea name="cover_letter" rows="8"
                                  class="form-control @error('cover_letter') is-invalid @enderror"
                                  required
                placeholder="Tell us why you are the ideal candidate for this role. Highlight your relevant skills, experience and motivation for joining Kikosi Kazi...">{{ old('cover_letter') }}</textarea>
                        @error('cover_letter')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="alert alert-light border mb-4">
                        <i class="bi bi-info-circle-fill me-2 text-primary"></i>
                        <strong>Note:</strong> Your saved profile information (personal details, education, experience and documents)
                        will be submitted automatically alongside this application.
                    </div>

                    <div class="d-flex gap-3">
                        <button type="submit" class="btn fw-semibold px-5" style="background:var(--kk-blue);color:#fff">
                            <i class="bi bi-send-fill me-2"></i>Submit Application
                        </button>
                        <a href="{{ route('careers.show', $job) }}" class="btn btn-outline-secondary px-4">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- RIGHT: Tips --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h6 class="fw-bold mb-0" style="color:var(--kk-blue)"><i class="bi bi-lightbulb-fill me-2"></i>Application Tips</h6>
            </div>
            <div class="card-body p-4">
                <ul class="list-unstyled mb-0">
                    <li class="d-flex align-items-start gap-2 mb-3">
                        <i class="bi bi-check-circle-fill text-success mt-1"></i>
                        <span class="small text-muted">Complete your profile before applying — it is submitted automatically.</span>
                    </li>
                    <li class="d-flex align-items-start gap-2 mb-3">
                        <i class="bi bi-check-circle-fill text-success mt-1"></i>
                        <span class="small text-muted">Tailor your cover letter to the specific role and department.</span>
                    </li>
                    <li class="d-flex align-items-start gap-2 mb-3">
                        <i class="bi bi-check-circle-fill text-success mt-1"></i>
                        <span class="small text-muted">Upload your CV and certificates in the Documents section for a complete application.</span>
                    </li>
                    <li class="d-flex align-items-start gap-2">
                        <i class="bi bi-check-circle-fill text-success mt-1"></i>
                        <span class="small text-muted">Check the deadline — late applications will not be considered.</span>
                    </li>
                </ul>
                <hr>
                <a href="{{ route('candidate.profile.index') }}" class="btn btn-outline-primary btn-sm w-100">
                    <i class="bi bi-person-circle me-1"></i>Update My Profile
                </a>
                <a href="{{ route('candidate.documents.index') }}" class="btn btn-outline-secondary btn-sm w-100 mt-2">
                    <i class="bi bi-folder2-open me-1"></i>Manage Documents
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
