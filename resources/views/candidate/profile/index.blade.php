@extends('layouts.candidate')
@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')

@php
    $pct  = $profile->completeness_pct ?? 0;
    $dash = 283;
    $fill = round(($pct / 100) * $dash);
    $col  = $pct >= 100 ? '#2E7D32' : ($pct >= 60 ? '#F9A825' : '#C62828');
    $educations  = $profile->education  ?? collect();
    $experiences = $profile->experience ?? collect();
@endphp

{{-- TOP HEADER --}}
<div class="row g-4 align-items-stretch mb-4">

    {{-- Left: heading + photo --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 h-100 p-4">
            <div class="d-flex gap-4 align-items-center flex-wrap">
                {{-- Profile photo --}}
                <div class="position-relative flex-shrink-0">
                    @if($profile->profile_photo)
                    <img src="{{ asset('storage/' . $profile->profile_photo) }}" alt="Profile Photo"
                         class="rounded-circle shadow"
                         style="width:100px;height:100px;object-fit:cover;border:4px solid var(--kk-blue,#0D47A1)">
                    @else
                    <div class="rounded-circle shadow d-flex align-items-center justify-content-center"
                         style="width:100px;height:100px;background:#E8F0FE;border:4px solid var(--kk-blue,#0D47A1)">
                        <i class="bi bi-person-fill fs-1" style="color:var(--kk-blue,#0D47A1)"></i>
                    </div>
                    @endif
                </div>
                <div class="flex-fill">
                    <h4 class="fw-bold mb-1">{{ Auth::user()->name }}</h4>
                    <p class="text-muted mb-2 small">{{ Auth::user()->email }}</p>
                    <div class="d-flex flex-wrap gap-2">
                        @if($pct >= 100)
                        <a href="{{ route('candidate.cv.show') }}" target="_blank"
                           class="btn btn-success btn-sm fw-semibold">
                            <i class="bi bi-file-earmark-pdf-fill me-1"></i>Download CV (PDF)
                        </a>
                        @else
                        <span class="btn btn-sm btn-outline-secondary disabled" title="Complete your profile to unlock CV download">
                            <i class="bi bi-lock-fill me-1"></i>CV Locked — {{ $pct }}% Complete
                        </span>
                        @endif
                        <a href="{{ route('candidate.documents.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-folder2 me-1"></i>My Documents
                        </a>
                    </div>
                </div>
            </div>

            {{-- Completeness bar --}}
            <div class="mt-4">
                <div class="d-flex justify-content-between mb-1">
                    <span class="small fw-semibold text-muted">Profile Completeness</span>
                    <span class="small fw-bold" style="color:{{ $col }}">{{ $pct }}%</span>
                </div>
                <div class="progress rounded-pill" style="height:10px">
                    <div class="progress-bar rounded-pill" role="progressbar"
                         style="width:{{ $pct }}%;background:{{ $col }}"
                         aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                @if($pct < 100)
                <p class="text-muted mt-2 mb-0 small">
                    <i class="bi bi-info-circle-fill me-1"></i>
                    Fill all required fields below, add at least <strong>one education</strong> and <strong>one experience</strong> record, and upload your <strong>academic certificate</strong> in <a href="{{ route('candidate.documents.index') }}" class="fw-semibold">My Documents</a> to reach 100%.
                </p>
                @else
                <p class="text-success mt-2 mb-0 small fw-semibold">
                    <i class="bi bi-check-circle-fill me-1"></i>
                    Your profile is complete! You can now apply for jobs and download your CV.
                </p>
                @endif
            </div>
        </div>
    </div>

    {{-- Right: checklist --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 p-4">
            <h6 class="fw-bold text-muted text-uppercase small mb-3">Completion Checklist</h6>
            @php
                $checks = [
                    ['label' => 'Phone Number',      'done' => !empty($profile->phone)],
                    ['label' => 'Gender',             'done' => !empty($profile->gender)],
                    ['label' => 'Date of Birth',      'done' => !empty($profile->date_of_birth)],
                    ['label' => 'Nationality',        'done' => !empty($profile->nationality)],
                    ['label' => 'Address',            'done' => !empty($profile->address)],
                    ['label' => 'City',               'done' => !empty($profile->city)],
                    ['label' => 'Region',             'done' => !empty($profile->region)],
                    ['label' => 'Education (≥1)',     'done' => $educations->count() > 0],
                    ['label' => 'Experience (≥1)',    'done' => $experiences->count() > 0],
                    ['label' => 'Academic Certificate','done' => $profile->documents()->where('file_type','certificate')->exists()],
                ];
            @endphp
            <ul class="list-unstyled mb-0">
                @foreach($checks as $chk)
                <li class="d-flex align-items-center gap-2 py-1 border-bottom {{ $loop->last ? 'border-0' : '' }}">
                    @if($chk['done'])
                    <i class="bi bi-check-circle-fill text-success flex-shrink-0"></i>
                    <span class="small text-muted text-decoration-line-through">{{ $chk['label'] }}</span>
                    @else
                    <i class="bi bi-circle-fill text-muted flex-shrink-0"></i>
                    <span class="small fw-semibold text-dark">{{ $chk['label'] }}</span>
                    @endif
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

{{-- TABS --}}
<ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal"
                type="button" role="tab">
            <i class="bi bi-person-fill me-1"></i>Personal Info
            @if(!$profile->phone || !$profile->gender || !$profile->date_of_birth || !$profile->nationality || !$profile->address || !$profile->city || !$profile->region)
            <span class="badge bg-danger ms-1 rounded-pill" style="font-size:.6rem">!</span>
            @endif
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="education-tab" data-bs-toggle="tab" data-bs-target="#education"
                type="button" role="tab">
            <i class="bi bi-mortarboard-fill me-1"></i>Education
            @if($educations->count() == 0)
            <span class="badge bg-danger ms-1 rounded-pill" style="font-size:.6rem">!</span>
            @else
            <span class="badge bg-success ms-1 rounded-pill" style="font-size:.6rem">{{ $educations->count() }}</span>
            @endif
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="experience-tab" data-bs-toggle="tab" data-bs-target="#experience"
                type="button" role="tab">
            <i class="bi bi-briefcase-fill me-1"></i>Experience
            @if($experiences->count() == 0)
            <span class="badge bg-danger ms-1 rounded-pill" style="font-size:.6rem">!</span>
            @else
            <span class="badge bg-success ms-1 rounded-pill" style="font-size:.6rem">{{ $experiences->count() }}</span>
            @endif
        </button>
    </li>
</ul>

<div class="tab-content" id="profileTabsContent">

    {{-- ============ PERSONAL INFO TAB ============ --}}
    <div class="tab-pane fade show active" id="personal" role="tabpanel">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <form action="{{ route('candidate.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <p class="text-muted small mb-4">
                        Fields marked <span class="text-danger fw-bold">*</span> are required to reach 100% profile completeness.
                    </p>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold small">
                                Profile Photo <span class="text-danger">*</span>
                            </label>
                            @if($profile->profile_photo)
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <img src="{{ asset('storage/' . $profile->profile_photo) }}"
                                     class="rounded-circle" style="width:56px;height:56px;object-fit:cover;border:2px solid var(--kk-blue,#0D47A1)"
                                     alt="Current photo">
                                <span class="text-success small"><i class="bi bi-check-circle-fill me-1"></i>Photo uploaded — upload a new file to replace it</span>
                            </div>
                            @else
                            <div class="alert alert-warning py-2 px-3 small mb-2">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                A profile photo is required to reach 100% and apply for jobs.
                            </div>
                            @endif
                            <input type="file" name="profile_photo"
                                   class="form-control @error('profile_photo') is-invalid @enderror"
                                   accept="image/jpeg,image/png,image/webp">
                            <div class="form-text">JPG/PNG/WebP, max 2MB.</div>
                            @error('profile_photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" name="phone"
                                   class="form-control @error('phone') is-invalid @enderror {{ !empty($profile->phone) ? 'is-valid' : '' }}"
                                   value="{{ old('phone', $profile->phone ?? '') }}" placeholder="+255 700 000 000" required>
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Alternate Phone <span class="text-muted fw-normal">(optional)</span></label>
                            <input type="tel" name="alternate_phone"
                                   class="form-control @error('alternate_phone') is-invalid @enderror"
                                   value="{{ old('alternate_phone', $profile->alternate_phone ?? '') }}" placeholder="+255 700 000 001">
                            @error('alternate_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Gender <span class="text-danger">*</span></label>
                            <select name="gender"
                                    class="form-select @error('gender') is-invalid @enderror {{ !empty($profile->gender) ? 'is-valid' : '' }}" required>
                                <option value="">-- Select Gender --</option>
                                <option value="male"   {{ old('gender', $profile->gender ?? '') === 'male'   ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $profile->gender ?? '') === 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other"  {{ old('gender', $profile->gender ?? '') === 'other'  ? 'selected' : '' }}>Other / Prefer not to say</option>
                            </select>
                            @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" name="date_of_birth"
                                   class="form-control @error('date_of_birth') is-invalid @enderror {{ !empty($profile->date_of_birth) ? 'is-valid' : '' }}"
                                   value="{{ old('date_of_birth', isset($profile->date_of_birth) ? $profile->date_of_birth->format('Y-m-d') : '') }}" required>
                            @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">National ID Number <span class="text-muted fw-normal">(optional)</span></label>
                            <input type="text" name="national_id"
                                   class="form-control @error('national_id') is-invalid @enderror"
                                   value="{{ old('national_id', $profile->national_id ?? '') }}" placeholder="e.g. 19XXXXXXXXXX">
                            @error('national_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Nationality <span class="text-danger">*</span></label>
                            <input type="text" name="nationality"
                                   class="form-control @error('nationality') is-invalid @enderror {{ !empty($profile->nationality) ? 'is-valid' : '' }}"
                                   value="{{ old('nationality', $profile->nationality ?? '') }}" placeholder="e.g. Tanzanian" required>
                            @error('nationality')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Residential Address <span class="text-danger">*</span></label>
                            <input type="text" name="address"
                                   class="form-control @error('address') is-invalid @enderror {{ !empty($profile->address) ? 'is-valid' : '' }}"
                                   value="{{ old('address', $profile->address ?? '') }}" placeholder="Street / House Number / Area" required>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">City / Town <span class="text-danger">*</span></label>
                            <input type="text" name="city"
                                   class="form-control @error('city') is-invalid @enderror {{ !empty($profile->city) ? 'is-valid' : '' }}"
                                   value="{{ old('city', $profile->city ?? '') }}" placeholder="e.g. Dar es Salaam" required>
                            @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Region <span class="text-danger">*</span></label>
                            @php $curRegion = old('region', $profile->region ?? ''); @endphp
                            <select name="region" id="regionSelect"
                                    class="form-select @error('region') is-invalid @enderror {{ !empty($profile->region) ? 'is-valid' : '' }}" required>
                                <option value="">— Select or type your region —</option>
                                @foreach(\App\Models\Job::TZ_REGIONS as $region)
                                <option value="{{ $region }}" {{ $curRegion === $region ? 'selected' : '' }}>{{ $region }}</option>
                                @endforeach
                                @if($curRegion && !in_array($curRegion, \App\Models\Job::TZ_REGIONS))
                                <option value="{{ $curRegion }}" selected>{{ $curRegion }}</option>
                                @endif
                            </select>
                            <div class="form-text small">Pick your Tanzania region — or type your own if you're outside Tanzania.</div>
                            @error('region')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

                            @push('styles')
                            <link href="https://cdnjs.cloudflare.com/ajax/libs/tom-select/2.3.1/css/tom-select.bootstrap5.min.css" rel="stylesheet">
                            <style>.ts-wrapper.form-select{padding:0}.ts-control{border-radius:.375rem}</style>
                            @endpush
                            @push('scripts')
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/tom-select/2.3.1/js/tom-select.complete.min.js"></script>
                            <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                if (window.TomSelect && document.getElementById('regionSelect')) {
                                    new TomSelect('#regionSelect', {
                                        create: true, createOnBlur: true,
                                        sortField: { field: 'text', direction: 'asc' },
                                    });
                                }
                            });
                            </script>
                            @endpush
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Bio / Personal Statement <span class="text-muted fw-normal">(optional)</span></label>
                            <textarea name="bio" rows="4"
                                      class="form-control @error('bio') is-invalid @enderror"
                                      placeholder="Briefly describe yourself, your skills and career goals...">{{ old('bio', $profile->bio ?? '') }}</textarea>
                            @error('bio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 pt-2">
                            <button type="submit" class="btn fw-semibold px-5 py-2" style="background:var(--kk-blue,#0D47A1);color:#fff">
                                <i class="bi bi-save-fill me-2"></i>Save Personal Info
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ============ EDUCATION TAB ============ --}}
    <div class="tab-pane fade" id="education" role="tabpanel">

        @if($educations->isEmpty())
        <div class="alert alert-warning rounded-3 shadow-sm mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Required:</strong> Add at least one education record to reach 100% profile completeness and become eligible to apply for jobs.
        </div>
        @endif

        {{-- Existing Records --}}
        @if($educations->count())
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Education History</h5>
                <span class="badge bg-success px-3 py-2">{{ $educations->count() }} record{{ $educations->count() != 1 ? 's' : '' }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3">Institution</th>
                                <th>Qualification</th>
                                <th>Field of Study</th>
                                <th>Year</th>
                                <th>Grade</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($educations as $edu)
                            <tr>
                                <td class="px-4 fw-semibold small">{{ $edu->institution }}</td>
                                <td class="small">{{ $edu->qualification }}</td>
                                <td class="small text-muted">{{ $edu->field_of_study }}</td>
                                <td class="small fw-semibold">{{ $edu->year_completed }}</td>
                                <td class="small">{{ $edu->grade ?? '—' }}</td>
                                <td>
                                    <form action="{{ route('candidate.profile.education.destroy', $edu) }}" method="POST"
                                          onsubmit="return confirm('Delete this education record?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

                {{-- Add Education Form --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0"><i class="bi bi-plus-lg me-2"></i>Add Education Record</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('candidate.profile.education.store') }}" method="POST" id="eduSmartForm" onsubmit="return kkEduValidate()">
                    @csrf

                    {{-- Hidden fields that hold the final submitted values --}}
                    <input type="hidden" name="institution"    id="hidInstitution">
                    <input type="hidden" name="qualification"  id="hidQualification">
                    <input type="hidden" name="field_of_study" id="hidFieldOfStudy">

                    <div class="row g-3">

                        {{-- STEP 1: Level --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Education Level <span class="text-danger">*</span></label>
                            <select id="eduLevel" class="form-select" onchange="kkEduLevel(this.value)" required>
                                <option value="">— Select Education Level —</option>
                                <optgroup label="Higher Education (TCU)">
                                    <option value="phd">PhD / Doctorate</option>
                                    <option value="masters">Master's Degree</option>
                                    <option value="bachelor">Bachelor's Degree</option>
                                    <option value="adv_diploma">Advanced Diploma</option>
                                </optgroup>
                                <optgroup label="Technical Education (NACTVET / NTA)">
                                    <option value="ord_diploma">Ordinary Diploma</option>
                                    <option value="certificate">Certificate (NTA Level 4)</option>
                                </optgroup>
                                <optgroup label="Secondary School">
                                    <option value="form6">Form 6 / A-Level (ACSEE)</option>
                                    <option value="form4">Form 4 / O-Level (CSEE)</option>
                                </optgroup>
                                <optgroup label="Other">
                                    <option value="abroad">Studied Abroad / Not Listed</option>
                                </optgroup>
                            </select>
                        </div>

                        {{-- STEP 2: Tanzania / Abroad toggle (hidden for Form 4/6 and Abroad) --}}
                        <div class="col-md-6 d-none" id="eduLocRow">
                            <label class="form-label fw-semibold small">Study Location</label>
                            <div class="d-flex gap-2 mt-1">
                                <div class="form-check me-3">
                                    <input class="form-check-input" type="radio" name="edu_location" id="eduLocTZ" value="tz" checked onchange="kkEduLoc('tz')">
                                    <label class="form-check-label fw-semibold" for="eduLocTZ"><i class="bi bi-flag-fill me-1"></i>Tanzania</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="edu_location" id="eduLocAbroad" value="abroad" onchange="kkEduLoc('abroad')">
                                    <label class="form-check-label fw-semibold" for="eduLocAbroad"><i class="bi bi-globe me-1"></i>Abroad</label>
                                </div>
                            </div>
                        </div>

                        {{-- SECTION A: Tanzania Higher Education (TCU institution + degree programme) --}}
                        <div class="col-12 d-none" id="eduSecTCU">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">Institution <span class="text-danger">*</span></label>
                                    <div class="kk-ac-wrap">
                                        <input type="text" id="tcuInstInput" class="form-control" placeholder="Type to search universities / colleges..." autocomplete="off" oninput="kkAcFilter('tcu',this.value)" onblur="kkAcBlur('tcu')" onfocus="kkAcFocus('tcu')">
                                        <div class="kk-ac-list d-none" id="tcu-ac-list"></div>
                                    </div>
                                    <div class="form-text">Search from TCU-registered institutions or type manually</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">Programme / Field of Study <span class="text-danger">*</span></label>
                                    <div class="kk-ac-wrap">
                                        <input type="text" id="tcuProgInput" class="form-control" placeholder="e.g. Computer Science, Business Administration..." autocomplete="off" oninput="kkAcFilter('tcuprog',this.value)" onblur="kkAcBlur('tcuprog')" onfocus="kkAcFocus('tcuprog')">
                                        <div class="kk-ac-list d-none" id="tcuprog-ac-list"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- SECTION B: Tanzania Technical/NTA institution + NTA programme --}}
                        <div class="col-12 d-none" id="eduSecNTA">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">Institution <span class="text-danger">*</span></label>
                                    <div class="kk-ac-wrap">
                                        <input type="text" id="ntaInstInput" class="form-control" placeholder="Type to search colleges / institutes..." autocomplete="off" oninput="kkAcFilter('nta',this.value)" onblur="kkAcBlur('nta')" onfocus="kkAcFocus('nta')">
                                        <div class="kk-ac-list d-none" id="nta-ac-list"></div>
                                    </div>
                                    <div class="form-text">Search from NACTVET-registered institutions or type manually</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">Programme / Course <span class="text-danger">*</span></label>
                                    <div class="kk-ac-wrap">
                                        <input type="text" id="ntaProgInput" class="form-control" placeholder="e.g. Ordinary Diploma in Nursing..." autocomplete="off" oninput="kkAcFilter('ntaprog',this.value)" onblur="kkAcBlur('ntaprog')" onfocus="kkAcFocus('ntaprog')">
                                        <div class="kk-ac-list d-none" id="ntaprog-ac-list"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- SECTION C: Form 4 (O-Level) --}}
                        <div class="col-12 d-none" id="eduSecForm4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">School Name <span class="text-danger">*</span></label>
                                    <input type="text" id="f4School" class="form-control" placeholder="e.g. Kilakala Secondary School, Morogoro">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small">Division</label>
                                    <select id="f4Division" class="form-select">
                                        <option value="">— Select —</option>
                                        <option value="Division I">Division I</option>
                                        <option value="Division II">Division II</option>
                                        <option value="Division III">Division III</option>
                                        <option value="Division IV">Division IV</option>
                                        <option value="Division 0">Division 0</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small">Subjects Passed <span class="text-muted fw-normal">(optional)</span></label>
                                    <input type="text" id="f4Subjects" class="form-control" placeholder="e.g. Mathematics, English...">
                                </div>
                            </div>
                        </div>

                        {{-- SECTION D: Form 6 (A-Level) --}}
                        <div class="col-12 d-none" id="eduSecForm6">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small">School Name <span class="text-danger">*</span></label>
                                    <input type="text" id="f6School" class="form-control" placeholder="e.g. Kibaha High School, Pwani">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small">Combination / Stream</label>
                                    <input type="text" id="f6Stream" class="form-control" placeholder="e.g. PCM, HGE, CBG">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small">Points / Grade</label>
                                    <input type="text" id="f6Points" class="form-control" placeholder="e.g. 15 points">
                                </div>
                            </div>
                        </div>

                        {{-- SECTION E: Abroad / Manual entry --}}
                        <div class="col-12 d-none" id="eduSecAbroad">
                            <div class="row g-3">
                                <div class="col-md-5">
                                    <label class="form-label fw-semibold small">Institution Name <span class="text-danger">*</span></label>
                                    <input type="text" id="abrInst" class="form-control" placeholder="e.g. University of Cape Town">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold small">Country <span class="text-danger">*</span></label>
                                    <input type="text" id="abrCountry" class="form-control" placeholder="e.g. South Africa">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small">Programme / Field of Study <span class="text-danger">*</span></label>
                                    <input type="text" id="abrProg" class="form-control" placeholder="e.g. BSc Computer Science">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold small">Full Qualification Name <span class="text-danger">*</span></label>
                                    <input type="text" id="abrQual" class="form-control" placeholder="e.g. Bachelor of Science in Computer Science">
                                    <div class="form-text">Enter the exact qualification as written on your certificate</div>
                                </div>
                            </div>
                        </div>

                        {{-- COMMON: Year + Grade --}}
                        <div class="col-md-3 d-none" id="eduYearRow">
                            <label class="form-label fw-semibold small">Year Completed <span class="text-danger">*</span></label>
                            <input type="number" name="year_completed" id="eduYear" class="form-control"
                                   min="1950" max="{{ date('Y') }}" placeholder="{{ date('Y') }}" required>
                        </div>
                        <div class="col-md-3 d-none" id="eduGradeRow">
                            <label class="form-label fw-semibold small" id="eduGradeLabel">Grade / Result</label>
                            <input type="text" name="grade" id="eduGrade" class="form-control" placeholder="e.g. B+ / GPA 3.5">
                        </div>

                        {{-- Submit --}}
                        <div class="col-12 d-none" id="eduSubmitRow">
                            <button type="submit" class="btn fw-semibold px-5 py-2"
                                    style="background:var(--kk-blue,#0D47A1);color:#fff">
                                <i class="bi bi-plus-circle-fill me-2"></i>Add Education Record
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        {{-- Autocomplete CSS + Smart Education JS --}}
        <style>
        .kk-ac-wrap { position: relative; }
        .kk-ac-list {
            position: absolute; top: 100%; left: 0; right: 0; z-index: 1050;
            background: #fff; border: 1px solid #dee2e6; border-top: none;
            border-radius: 0 0 .5rem .5rem; max-height: 240px; overflow-y: auto;
            box-shadow: 0 8px 24px rgba(0,0,0,.12);
        }
        .kk-ac-item {
            padding: .45rem .75rem; cursor: pointer; font-size: .83rem;
            border-bottom: 1px solid #f1f1f1;
            transition: background .1s;
        }
        .kk-ac-item:hover, .kk-ac-item.active { background: #EEF2FF; }
        .kk-ac-item mark { background: #FFF3CD; padding: 0; font-weight: 600; }
        .kk-ac-empty { padding: .5rem .75rem; font-size: .8rem; color: #888; font-style: italic; }
        </style>

        <script>
        // ── Institution & Programme Data ──────────────────────────────────────────
        const KK_TCU = ["Abdulrahman Al- Sumait University (SUMAIT), Zanzibar", "Aga Khan University (AKU), Dar es Salaam", "Archbishop Mihayo University College of Tabora (AMUCTA), Tabora", "Ardhi University (ARU), Dar es Salaam", "Arusha Technical College (ATC), Arusha", "Catholic University of Health and Allied Sciences (CUHAS), Mwanza", "Catholic University of Mbeya (CUoM), Mbeya", "Dr. Salim Ahmed Salim Centre for Foreign Relations (CFR), Dar es Salaam", "Dar es Salaam Institute of Technology (DIT), Dar es Salaam", "Dar es Salaam Institute of Technology (DIT), Mwanza Campus", "Dar es Salaam Maritime Institute (DMI), Dar es Salaam", "Dar es Salaam Tumaini University (DarTU), Dar es Salaam", "Dar es Salaam University College of Education (DUCE), Dar es Salaam", "Eastern Africa Statistical Training Centre (EASTC), Dar es Salaam", "Jordan University College (JUCo), Morogoro", "Kampala International University in Tanzania (KIUT), Dar es Salaam", "Kairuki University (KU), Dar es Salaam", "Karume Institute of Science and Technology (KIST), Zanzibar", "KCMC University, Kilimanjaro", "Kizumbi Institute of Co-operative and Business Education (KICoB), Shinyanga", "Local Government Training Institute (LGTI), Dodoma", "Marian University College (MARUCo), Bagamoyo", "Mbeya University of Science and Technology (MUST), Mbeya", "Mbeya University of Science and Technology (MUST)– Rukwa Campus College", "Mkwawa University College of Education (MUCE), Iringa", "Moshi Co-operative University (MoCU), Kilimanjaro", "MS Training Centre for Development Cooperation (MS-TCDC), Arusha", "Muhimbili University of Health and Allied Sciences (MUHAS), Dar es Salaam", "Muslim University of Morogoro (MUM), Morogoro", "Mwalimu Nyerere Memorial Academy (MNMA), Dar es Salaam", "Mwalimu Nyerere Memorial Academy (MNMA), Zanzibar Campus", "Mwalimu Nyerere Memorial Academy (MNMA) - Pemba Campus", "Mwenge Catholic University (MWECAU), Kilimanjaro", "Mwenge Catholic University (MWECAU), Hedaru Campus College", "Mzumbe University (MU), Morogoro", "Mzumbe University Dar es Salaam Campus College (MUDCCo), Dar es Salaam", "Mzumbe University Mbeya Campus College (MUMCCo), Mbeya", "National Institute of Transport (NIT), Dar es Salaam", "Open University of Tanzania (OUT), Dar es Salaam", "Ruaha Catholic University (RUCU), Iringa", "Sokoine University of Agriculture (SUA), Morogoro", "Songea Catholic Institute of Technical Education (SOCAITE) , Songea", "St. Augustine University of Tanzania (SAUT), Mwanza", "St. Augustine University of Tanzania (SAUT) Arusha Centre, Arusha", "St. Francis University College of Health and Allied Sciences (SFUCHAS), Ifakara", "St. John's University of Tanzania (SJUT), Dodoma", "St. Joseph University College of Health and Allied Sciences (SJCHAS), Dar es Salaam", "St. Joseph University College of Engineering and Technology (SJCET), Dar es Salaam", "State University of Zanzibar (SUZA), Zanzibar", "Stella Maris Mtwara University College (STEMMUCo), Mtwara", "Tanzania Institute of Accountancy (TIA), Dar es Salaam", "Tanzania Institute of Accountancy (TIA), Mbeya", "Tanzania Institute of Accountancy (TIA), Mwanza", "Tanzania Institute of Accountancy (TIA), Mtwara", "Tanzania Institute of Accountancy (TIA), Singida", "Tanzania Institute of Accountancy (TIA), Kigoma", "Tanzania Institute of Accountancy (TIA), Tanga", "Tanzania Institute of Project Management (TIPM), Dar es Salaam", "Tanzania Public Service College (TPSC), Dar es Salaam", "Tanzania Public Service College (TPSC), Tabora", "Tengeru Institute of Community Development (TICD), Arusha", "Teofilo Kisanji University (TEKU), Mbeya", "Tumaini University Makumira (TUMA), Arusha", "Unique Academy Dar es Salaam (UAD)", "United African University of Tanzania (UAUT), Dar es Salaam", "Water Institute (WI), Dar es Salaam", "Zanzibar University (ZU), Zanzibar", "University of Dar es Salaam (UDSM), Dar es Salaam", "University of Dodoma (UDOM), Dodoma", "University of Iringa (UoI), Iringa", "University of Arusha (UoA), Arusha", "Mwalimu Nyerere University of Agriculture and Technology (MNUAT), Musoma"];
        const KK_NTA = ["Accra College of Health and Allied Sciences", "Agency for Development of Educational Management - Mwanza", "Agency for the Development of Educational Management - Bagamoyo", "Agency for the Development of Educational Management - Mbeya", "Al-Maktoum College of Engineering and Technology", "Amani College of Management and Technology (Acmt) - Njombe", "Amenye Health Training Institute", "Ardhi Institute - Tabora", "Ardhi Institute Morogoro", "Arusha Adventist College", "Arusha College of Administration", "Arusha Institute of Business Studies", "Arusha Lutheran Medical Training Centre", "Arusha Technical College - Arusha", "Bagamoyo School of Nursing", "Bandari College DAR-ES-SALAAM", "Bank of Tanzania Academy", "Becus Health Training Centre", "Beekeeping Training Institute - Tabora", "Benjamin Mkapa Institute of Health and Allied Sciences", "Berega Institute of Health Sciences", "Besha Health Training Institute", "Biharamulo College of Business and Technology", "Bishop Kisula College of Health and Allied Sciences", "Bishop Nicodemus Hhando College of Health Sciences", "Blue Pharma College of Health", "Borigaram Agriculture Technical College (Friends On the Path)", "Buhare Community Development Training Institute- Musoma", "Buhemba Community Development Training Institute (Buhemba Cdti) - Butiama", "Buhongwa College of Health and Allied Sciences", "Buhongwa College of Health and Allied Sciences - Usagara", "Bulongwa Health Sciences Institute", "Bumbuli College of Health and Allied Sciences", "Cardinal Rugambwa Memorial College", "Centre for Educational Development in Health Arusha", "Charlotte Institute of Health and Allied Sciences - Siha", "Chato College of Health Sciences and Technology", "City College of Health and Allied Sciences", "City College of Health and Allied Sciences - Arusha Campus", "City College of Health and Allied Sciences - Mwanza Campus", "City College of Health and Allied Sciences - Temeke Campus", "City College of Health and Allied Sciences, Dodoma Campus", "City Institute of Health and Allied Sciences", "Civil Aviation Training Centre", "Clinical Officers Training Centre Kigoma", "Clinical Officers Training Centre Maswa", "Clinical Officers Training Centre Musoma", "College of African Wildlife Management, Mweka - Moshi", "Comenius Polytechnic Institute", "Community Based Conservation Training Centre - Likuyu Sekamaganga", "Community Development Training Institute - Uyole, Mbeya", "Covenant Institute of Tanzania", "Dabaga Institute of Agriculture - Kilolo, Iringa", "DAR ES SALAAM Institute of Technology - Mwanza Campus", "DAR ES SALAAM Institute of Technology - Myunga Campus", "DAR ES SALAAM Maritime Institute", "DAR ES SALAAM Police Academy", "Dareda School of Nursing", "Dc Polytechnic Education Institute", "Decca College of Health and Allied Sciences - Nala Campus, Dodoma", "Divine College of Health and Allied Sciences", "Dodoma Institute of Development and Entrepreneurship Dodoma", "Dodoma Institute of Health and Allied Sciences", "Don Bosco Technical Training College - Dodoma", "Earth Science Institute of Shinyanga (ESIS)", "East Evans College of Health and Allied Sciences", "Eastern Africa Statistical Training Centre - DAR-ES-SALAAM", "Ecassa Institute of Social Protection", "Elijerry College of Health and Allied Sciences", "Excellent College of Health and Allied Sciences - Arusha Campus", "Excellent College of Health and Allied Sciences - Kibaha", "Excellent College of Health and Allied Sciences - Mbeya", "Excellent College of Health and Allied Sciences - Mwanza Campus", "Faraja Health Training Institute", "Fire and Rescue Training Institute", "Fisheries Education and Training Agency (FETA) - Nyegezi Campus", "Fisheries Education and Training Agency (FETA) Kigoma", "Fisheries Education and Training Agency (FETA) Mbegani", "Forest Industries Training Institute (FITI)", "Forestry Training Institute Olmotonyi", "Geita School of Nursing", "Glorious Polytechnic College", "Gold Seal Medical College", "Green Bird College - Mwanga", "Habari Maalum College (Hmc)", "Hagafilo College of Development Management", "Haydom Institute of Health Sciences", "Heri College of Health and Allied Sciences", "Hermargs Institute", "Hisani Institute of Health and Allied Sciences", "Horticultural Research and Training Institute Tengeru - Arusha", "Huruma Institute of Health and Allied Sciences", "Iambi Nursing School", "Igabiro Training Institute of Agriculture - Muleba", "Ilula Nursing School", "Imani College of Health and Allied Sciences", "Imperial College of Health and Allied Sciences", "Institute of Construction Technology", "Institute of Continuing and Professional Studies - Zanzibar", "Institute of Environment, Climate and Development Sustainability - DAR ES SALAAM", "Institute of Finance Management (IFM) - Geita Campus Chato", "Institute of Finance Management - DAR ES SALAAM", "Institute of Finance Management - Dodoma Campus", "Institute of Finance Management - Mwanza Campus", "Institute of Finance Management - Simiyu Campus", "Institute of Heavy Equipment and Technology", "Institute of Professional and Innovational Development(Ipid)", "Institute of Rural Development Planning - Dodoma", "Institute of Rural Development Planning - Mwanza", "Isimila Nursing School", "Janesa Institute of Health and Allied Sciences - Dodoma", "Jr Institute of Information Technology", "K'S Royal College of Health Sciences", "Kabanga College of Health and Allied Sciences", "Kahama College of Health Sciences", "Kahama School of Nursing", "Kairuki School of Nursing", "Kaliua Institute of Community Development - Mwanza Campus", "Kaliua Institute of Community Development -Tabora", "Kam College of Health Sciences", "Kange College of Health and Allied Sciences", "Kaps Community Development Institute - Mafinga", "Karatu Health Training Institute", "Karuco College", "Karume Institute of Science and Technology- Zanzibar", "Kasulu College of Health, Allied Sciences and Technology", "Katavi Institute of Science and Development Studies", "Kibaha College of Health and Allied Sciences", "Kibondo Clinical Officers Training Centre", "Kibondo School of Nursing", "Kigamboni City College of Health and Allied Sciences", "Kigoma Training College", "Kilacha Agriculture and Livestock Training Institute", "Kilema College of Health Sciences", "Kilenzi Memorial College of Health and Allied Sciences", "Kilimanjaro Institute of Health Sciences", "Kilimanjaro Institute of Technology and Management", "Kilimanjaro International Institute for Telecommunications Electronics and Computers", "Kilimanjaro School of Pharmacy", "Kilimatinde Institute of Health and Allied Sciences", "Kilosa Clinical Officers Training Centre", "Kiomboi School of Nursing", "Kisare College of Health Sciences", "Kiuma College of Health and Allied Sciences", "Kolandoto College of Health and Allied Sciences, Mwanza Campus", "Kolandoto College of Health Sciences", "Kolowa Technical Training Institution", "Kondoa School of Nursing", "Lake Institute of Health and Allied Sciences", "Landmark Institute of Education Science and Technology", "Law School of Tanzania", "Litembo Health Training Institute", "Local Government Training Institute Hombolo - Dodoma", "Lugalo Military Medical School", "Lugarawa Health Training Institute (Luheti)", "Machame Health Training Institute", "Macwish College of Health and Allied Sciences", "Mahinya College of Sustainable Agriculture", "Makambako Institute of Health Sciences", "Makumira Training Institute (Mti)", "Malya College of Sports Development", "Mamre Agriculture and Livestock College", "Manyara Institute of Health and Allied Sciences", "Masoka Professionals Training Institute", "Massana College of Health and Allied Sciences", "Mayday Institute of Health Sciences and Technology", "Mbalizi Institute of Health Sciences - Mbeya", "Mbalizi Polytechnic College - Mbeya", "Mbeya College of Health and Allied Sciences", "Mbeya Polytechnic College Tukuyu Campus", "Mbonye Training College", "Mbozi School of Nursing", "Mbulu School of Nursing", "Medical Missionaries of Mary School of Pharmaceutical Sciences", "Mgao Health Training Institute", "Microtech Institute of Business and Technology - Zanzibar", "Military Aviation School", "Military College of Medical Sciences - Mwanza Campus", "Military College of Medical Sciences - Zanzibar Campus", "Mineral Resource Institute (Mri) - Nzega", "Mineral Resources Institute (Madini Institute) - Dodoma", "Ministry of Agriculture Training Institute - Mtwara", "Ministry of Agriculture Training Institute Igurusi - Mbeya"];
        const KK_DEGREE  = ["Accounting and Finance", "Business Administration", "Commerce", "Economics", "Public Administration", "Human Resource Management", "Marketing", "Entrepreneurship", "Procurement and Logistics Management", "Project Management", "Banking and Finance", "Business Information Technology", "Office Management and Administration", "Insurance and Risk Management", "Tourism and Hospitality Management", "Records and Archives Management", "Library and Information Science", "Computer Science", "Information Technology", "Software Engineering", "Data Science and Analytics", "Cybersecurity", "Electrical Engineering", "Civil Engineering", "Mechanical Engineering", "Architecture", "Building and Construction", "Electronics and Telecommunications Engineering", "Environmental Engineering", "Agricultural Engineering", "Chemical and Processing Engineering", "Mining Engineering", "Petroleum Engineering", "Medicine and Surgery (MBChB)", "Nursing", "Pharmacy", "Dentistry", "Medical Laboratory Sciences", "Public Health", "Physiotherapy", "Radiography and Diagnostic Imaging", "Optometry", "Nutrition and Dietetics", "Clinical Medicine", "Health Systems Management", "Occupational Therapy", "Environmental Health Sciences", "Epidemiology and Biostatistics", "Agriculture General", "Food Science and Technology", "Forestry", "Wildlife Management", "Veterinary Medicine", "Fisheries", "Horticulture", "Animal Science", "Agricultural Economics", "Environmental Science and Management", "Natural Resources Management", "Law (LLB)", "Social Work", "Development Studies", "Community Development", "Sociology", "Political Science", "Public Administration", "Social Policy", "Mass Communication", "Journalism", "Media Studies", "Education (Arts)", "Education (Science)", "Special Education", "Early Childhood Education", "Adult Education", "Physical Education and Sports Science", "Statistics", "Mathematics", "Physics", "Chemistry", "Biology", "Geography", "Geology", "Marine Science", "Linguistics and Literature", "Swahili Language and Literature", "History", "International Relations", "Gender Studies", "Land Management and Valuation", "Urban and Regional Planning", "Real Estate Management", "Geomatics and Remote Sensing", "Transport and Logistics Management", "Maritime Transport", "Civil Aviation Management"];
        const KK_MASTERS = ["Master of Business Administration (MBA)", "Master of Science in Computer Science", "Master of Science in Information Technology", "Master of Science in Data Science", "Master of Science in Electrical Engineering", "Master of Science in Civil Engineering", "Master of Science in Environmental Science", "Master of Science in Public Health", "Master of Public Health (MPH)", "Master of Science in Nursing", "Master of Science in Agriculture", "Master of Science in Economics", "Master of Arts in Development Studies", "Master of Arts in Mass Communication", "Master of Laws (LLM)", "Master of Education", "Master of Science in Mathematics", "Master of Science in Statistics", "Master of Project Management", "Master of Science in Finance", "Master of Science in Procurement and Supply Chain Management", "Master of Arts in Linguistics", "Master of Science in Urban Planning", "Master of Science in Land Management", "Master of Science in Wildlife Management", "Master of Science in Forestry", "Master of Science in Food Science", "Master of Science in Fisheries", "Master of Accountancy", "Master of Science in Human Resource Management", "Master of Science in Social Work", "Master of Arts in Sociology", "Master of Arts in Political Science", "Master of Science in Meteorology", "Master of Science in Geology", "Master of Science in Marine Biology", "Master of Science in Biochemistry", "Master of Science in Chemistry", "Master of Science in Physics", "Master of Science in Biology", "Master of Philosophy (MPhil)"];
        const KK_PHD     = ["Doctor of Philosophy (PhD) in Business Administration", "Doctor of Philosophy (PhD) in Computer Science", "Doctor of Philosophy (PhD) in Economics", "Doctor of Philosophy (PhD) in Education", "Doctor of Philosophy (PhD) in Engineering", "Doctor of Philosophy (PhD) in Environmental Science", "Doctor of Philosophy (PhD) in Law", "Doctor of Philosophy (PhD) in Medicine", "Doctor of Philosophy (PhD) in Nursing", "Doctor of Philosophy (PhD) in Public Health", "Doctor of Philosophy (PhD) in Agriculture", "Doctor of Philosophy (PhD) in Linguistics", "Doctor of Philosophy (PhD) in Sociology", "Doctor of Philosophy (PhD) in Political Science", "Doctor of Philosophy (PhD) in Statistics", "Doctor of Philosophy (PhD) in Mathematics", "Doctor of Philosophy (PhD) in Chemistry", "Doctor of Philosophy (PhD) in Biology", "Doctor of Philosophy (PhD) in Physics", "Doctor of Philosophy (PhD) in Geography", "Doctor of Medicine (MD)", "Doctor of Veterinary Medicine (DVM)", "Doctor of Business Administration (DBA)", "Doctor of Public Administration (DPA)", "Doctor of Laws (LLD)"];
        const KK_NTA_DIP = ["Ordinary Diploma in Clinical Medicine", "Ordinary Diploma in Nursing and Midwifery", "Ordinary Diploma in Pharmaceutical Sciences", "Ordinary Diploma in Medical Laboratory Sciences", "Ordinary Diploma in Physiotherapy", "Ordinary Diploma in Radiography", "Ordinary Diploma in Optometry", "Ordinary Diploma in Nutrition and Dietetics", "Ordinary Diploma in Public Health", "Ordinary Diploma in Dental Technology", "Ordinary Diploma in Environmental Health Sciences", "Ordinary Diploma in Orthopaedic Technology", "Ordinary Diploma in Occupational Therapy", "Ordinary Diploma in Accountancy", "Ordinary Diploma in Business Administration", "Ordinary Diploma in Finance and Banking", "Ordinary Diploma in Marketing", "Ordinary Diploma in Human Resources Management", "Ordinary Diploma in Procurement and Supply", "Ordinary Diploma in Procurement and Logistics Management", "Ordinary Diploma in Transport and Logistics", "Ordinary Diploma in Insurance and Risk Management", "Ordinary Diploma in Tourism and Hotel Management", "Ordinary Diploma in Secretarial Studies", "Ordinary Diploma in Office Management", "Ordinary Diploma in Records and Archives Management", "Ordinary Diploma in Library and Information Science", "Ordinary Diploma in Computing and Information Technology", "Ordinary Diploma in Computer Science", "Ordinary Diploma in Information Technology", "Ordinary Diploma in Software Development", "Ordinary Diploma in Networking and Data Communications", "Ordinary Diploma in Multimedia", "Ordinary Diploma in Cyber Security", "Ordinary Diploma in Electrical Engineering", "Ordinary Diploma in Electrical and Electronics Engineering", "Ordinary Diploma in Mechanical Engineering", "Ordinary Diploma in Civil and Building Construction", "Ordinary Diploma in Automotive Engineering", "Ordinary Diploma in Instrumentation Engineering", "Ordinary Diploma in Welding and Metal Fabrication", "Ordinary Diploma in Plumbing and Gas Fitting", "Ordinary Diploma in Water Supply Engineering", "Ordinary Diploma in Heavy Duty Equipment", "Ordinary Diploma in Laboratory Science and Technology", "Ordinary Diploma in Social Work", "Ordinary Diploma in Community Development", "Ordinary Diploma in Gender and Development", "Ordinary Diploma in Law", "Ordinary Diploma in Journalism", "Ordinary Diploma in Mass Communication", "Ordinary Diploma in Public Service Management", "Ordinary Diploma in Statistics", "Ordinary Diploma in Educational Leadership and Management", "Ordinary Diploma in Land Management and Valuation", "Ordinary Diploma in Urban and Regional Planning", "Ordinary Diploma in Environmental Management", "Ordinary Diploma in Geographical Information Systems", "Ordinary Diploma in Geomatics", "Ordinary Diploma in Graphic Arts and Printing", "Ordinary Diploma in Cartography", "Ordinary Diploma in Records, Archives and Information Management", "Ordinary Diploma in Agriculture", "Ordinary Diploma in Horticulture", "Ordinary Diploma in Animal Science", "Ordinary Diploma in Food Science and Technology", "Ordinary Diploma in Fisheries", "Ordinary Diploma in Forestry", "Ordinary Diploma in Wildlife Management", "Ordinary Diploma in Irrigation Engineering", "Ordinary Diploma in Transportation Engineering", "Ordinary Diploma in Maritime Studies", "Ordinary Diploma in Aviation", "Ordinary Diploma in Hotel and Catering Services", "Ordinary Diploma in Tourism Management", "Basic Technician Certificate in Information Technology", "Basic Technician Certificate in Business Administration", "Basic Technician Certificate in Accountancy", "Basic Technician Certificate in Social Work", "Basic Technician Certificate in Community Development", "Basic Technician Certificate in Electrical Engineering", "Basic Technician Certificate in Mechanical Engineering", "Basic Technician Certificate in Building Construction", "Basic Technician Certificate in Architecture", "Basic Technician Certificate in Journalism", "Basic Technician Certificate in Clinical Medicine", "Basic Technician Certificate in Pharmacy", "Basic Technician Certificate in Laboratory Sciences", "Basic Technician Certificate in Nursing", "Basic Technician Certificate in Agriculture", "Basic Technician Certificate in Horticulture", "Basic Technician Certificate in Food Processing", "Basic Technician Certificate in Records Management", "Basic Technician Certificate in Library Science", "Basic Technician Certificate in Tourism", "Basic Technician Certificate in Hotel and Catering", "Basic Technician Certificate in Procurement", "Basic Technician Certificate in Transport and Logistics", "Basic Technician Certificate in Motor Vehicle Mechanics", "Basic Technician Certificate in Welding and Fabrication", "Basic Technician Certificate in Plumbing", "Basic Technician Certificate in Electrical Installation"];

        // Autocomplete state
        const kkAcData = {
            tcu: KK_TCU, nta: KK_NTA,
            tcuprog: KK_DEGREE, ntaprog: KK_NTA_DIP
        };
        const kkAcSelected = { tcu: false, nta: false, tcuprog: false, ntaprog: false };
        let kkAcIdx = {};

        // ── Autocomplete engine ───────────────────────────────────────────────────
        function kkAcFilter(key, query) {
            kkAcSelected[key] = false;
            const list = document.getElementById(key + '-ac-list');
            const q = query.trim().toLowerCase();
            let src = kkAcData[key];

            // For programme, filter based on current level
            if (key === 'tcuprog') src = kkTCUProgList();
            if (key === 'ntaprog') src = kkNTAProgList();

            if (!q || q.length < 2) { list.classList.add('d-none'); return; }

            const matches = src.filter(s => s.toLowerCase().includes(q)).slice(0, 20);
            if (!matches.length) {
                list.innerHTML = '<div class="kk-ac-empty">No match — you can still type your own</div>';
            } else {
                list.innerHTML = matches.map((m, i) => {
                    const hl = m.replace(new RegExp('(' + q.replace(/[.*+?^${}()|[\\]\\\\]/g, '\\\\$&') + ')', 'gi'), '<mark>$1</mark>');
                    return '<div class="kk-ac-item" onmousedown="kkAcPick(\'' + key + '\', \'' + m.replace(/\'/g, '&#39;') + '\')">' + hl + '</div>';
                }).join('');
            }
            list.classList.remove('d-none');
            kkAcIdx[key] = -1;
        }

        function kkAcPick(key, value) {
            const inputs = { tcu: 'tcuInstInput', nta: 'ntaInstInput', tcuprog: 'tcuProgInput', ntaprog: 'ntaProgInput' };
            document.getElementById(inputs[key]).value = value;
            document.getElementById(key + '-ac-list').classList.add('d-none');
            kkAcSelected[key] = true;
            kkSyncHidden();
        }

        function kkAcBlur(key) {
            setTimeout(() => {
                const list = document.getElementById(key + '-ac-list');
                if (list) list.classList.add('d-none');
                kkSyncHidden();
            }, 200);
        }

        function kkAcFocus(key) {
            const inputs = { tcu: 'tcuInstInput', nta: 'ntaInstInput', tcuprog: 'tcuProgInput', ntaprog: 'ntaProgInput' };
            const val = document.getElementById(inputs[key])?.value || '';
            if (val.length >= 2) kkAcFilter(key, val);
        }

        // ── Programme list by level ───────────────────────────────────────────────
        function kkTCUProgList() {
            const level = document.getElementById('eduLevel').value;
            if (level === 'phd') return KK_PHD;
            if (level === 'masters') return KK_MASTERS;
            return KK_DEGREE; // bachelor / adv_diploma
        }
        function kkNTAProgList() {
            return KK_NTA_DIP;
        }

        // ── Level change handler ──────────────────────────────────────────────────
        function kkEduLevel(val) {
            const sections = ['eduLocRow','eduSecTCU','eduSecNTA','eduSecForm4','eduSecForm6','eduSecAbroad','eduYearRow','eduGradeRow','eduSubmitRow'];
            sections.forEach(id => document.getElementById(id)?.classList.add('d-none'));

            // Reset radio to TZ
            const locTZ = document.getElementById('eduLocTZ');
            if (locTZ) { locTZ.checked = true; }

            if (!val) return;

            if (val === 'form4') {
                show('eduSecForm4'); show('eduYearRow'); show('eduGradeRow'); show('eduSubmitRow');
                document.getElementById('eduGradeLabel').textContent = 'Division / Result';
                return;
            }
            if (val === 'form6') {
                show('eduSecForm6'); show('eduYearRow'); show('eduGradeRow'); show('eduSubmitRow');
                document.getElementById('eduGradeLabel').textContent = 'Points / Grade';
                return;
            }
            if (val === 'abroad') {
                show('eduSecAbroad'); show('eduYearRow'); show('eduGradeRow'); show('eduSubmitRow');
                document.getElementById('eduGradeLabel').textContent = 'Grade / Result';
                return;
            }

            // Higher education or NTA — show location toggle first
            show('eduLocRow');

            // Auto-show the right section for TZ (default checked)
            kkEduLoc('tz', val);

            document.getElementById('eduGradeLabel').textContent = 'Grade / GPA';

            // Update NTA programme list placeholder
            if (val === 'certificate') {
                const np = document.getElementById('ntaProgInput');
                if (np) np.placeholder = 'e.g. Basic Technician Certificate in IT...';
                // Filter ntaprog to show certificates
                kkAcData['ntaprog'] = KK_NTA_DIP.filter(p => p.startsWith('Basic') || p.startsWith('Certificate'));
            } else {
                kkAcData['ntaprog'] = KK_NTA_DIP;
                const np = document.getElementById('ntaProgInput');
                if (np) np.placeholder = 'e.g. Ordinary Diploma in Nursing...';
            }
        }

        function kkEduLoc(loc, level) {
            level = level || document.getElementById('eduLevel').value;
            const isTCU = ['phd','masters','bachelor','adv_diploma'].includes(level);

            hide('eduSecTCU'); hide('eduSecNTA'); hide('eduSecAbroad');
            hide('eduYearRow'); hide('eduGradeRow'); hide('eduSubmitRow');

            if (loc === 'abroad') {
                show('eduSecAbroad');
            } else {
                if (isTCU) show('eduSecTCU');
                else       show('eduSecNTA');
            }
            show('eduYearRow'); show('eduGradeRow'); show('eduSubmitRow');
        }

        function show(id) { document.getElementById(id)?.classList.remove('d-none'); }
        function hide(id) { document.getElementById(id)?.classList.add('d-none'); }

        // ── Sync hidden inputs before submit ──────────────────────────────────────
        function kkSyncHidden() {
            // Called on blur + before submit
        }

        function kkEduValidate() {
            const level = document.getElementById('eduLevel').value;
            if (!level) { alert('Please select your education level.'); return false; }

            let institution = '', qualification = '', field = '';
            const loc = document.querySelector('input[name="edu_location"]:checked')?.value || 'tz';
            const yr = document.getElementById('eduYear').value;
            if (!yr) { alert('Please enter the year completed.'); return false; }

            if (level === 'form4') {
                institution = document.getElementById('f4School').value.trim();
                if (!institution) { alert('Please enter your school name.'); return false; }
                qualification = 'CSEE (Certificate of Secondary Education Examination)';
                const div = document.getElementById('f4Division').value;
                const sub = document.getElementById('f4Subjects').value.trim();
                field = div ? (div + (sub ? ' — ' + sub : '')) : (sub || 'CSEE');
                if (!field) field = 'CSEE';
            }
            else if (level === 'form6') {
                institution = document.getElementById('f6School').value.trim();
                if (!institution) { alert('Please enter your school name.'); return false; }
                qualification = 'ACSEE (Advanced Certificate of Secondary Education Examination)';
                const str = document.getElementById('f6Stream').value.trim();
                const pts = document.getElementById('f6Points').value.trim();
                field = str || 'A-Level';
                if (pts && !document.getElementById('eduGrade').value)
                    document.getElementById('eduGrade').value = pts;
            }
            else if (level === 'abroad' || loc === 'abroad') {
                institution = document.getElementById('abrInst').value.trim();
                const country = document.getElementById('abrCountry').value.trim();
                if (!institution) { alert('Please enter institution name.'); return false; }
                if (country) institution = institution + ' (' + country + ')';
                qualification = document.getElementById('abrQual').value.trim() || '';
                field = document.getElementById('abrProg').value.trim();
                if (!field) { alert('Please enter your programme / field of study.'); return false; }
                if (!qualification) qualification = field;
            }
            else if (['phd','masters','bachelor','adv_diploma'].includes(level)) {
                institution = document.getElementById('tcuInstInput').value.trim();
                field = document.getElementById('tcuProgInput').value.trim();
                if (!institution) { alert('Please enter your institution name.'); return false; }
                if (!field) { alert('Please enter your programme / field of study.'); return false; }
                const prefixes = { phd:'Doctor of Philosophy (PhD) in', masters:'Master of Science in', bachelor:'Bachelor of Science in', adv_diploma:'Advanced Diploma in' };
                // If user typed a full qualification keep it, else auto-build
                qualification = field.match(/^(Bachelor|Master|Doctor|PhD|Advanced Diploma|Diploma)/i)
                    ? field : (prefixes[level] + ' ' + field);
            }
            else { // ord_diploma / certificate + tz
                institution = document.getElementById('ntaInstInput').value.trim();
                field = document.getElementById('ntaProgInput').value.trim();
                if (!institution) { alert('Please enter your institution name.'); return false; }
                if (!field) { alert('Please enter your programme / course.'); return false; }
                qualification = field.match(/^(Ordinary Diploma|Basic Technician|Advanced Diploma|Certificate)/i)
                    ? field : 'Ordinary Diploma in ' + field;
                // field_of_study = stripped version
                field = field.replace(/^(Ordinary Diploma in |Basic Technician Certificate in |Advanced Diploma in |Certificate in )/i, '').trim() || field;
            }

            document.getElementById('hidInstitution').value   = institution;
            document.getElementById('hidQualification').value = qualification;
            document.getElementById('hidFieldOfStudy').value  = field;
            return true;
        }
        </script>
    </div>

    {{-- ============ EXPERIENCE TAB ============ --}}
    <div class="tab-pane fade" id="experience" role="tabpanel">

        @if($experiences->isEmpty())
        <div class="alert alert-warning rounded-3 shadow-sm mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Required:</strong> Add at least one work experience record to reach 100% profile completeness and become eligible to apply for jobs.
        </div>
        @endif

        {{-- Existing Records --}}
        @if($experiences->count())
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Work Experience</h5>
                <span class="badge bg-success px-3 py-2">{{ $experiences->count() }} record{{ $experiences->count() != 1 ? 's' : '' }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3">Employer</th>
                                <th>Job Title</th>
                                <th>From</th>
                                <th>To</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($experiences as $exp)
                            <tr style="cursor:pointer" onclick="document.getElementById('resp-{{ $exp->id }}').classList.toggle('d-none')" title="Click to view responsibilities">
                                <td class="px-4 fw-semibold small">
                                    {{ $exp->employer }}
                                    @if($exp->responsibilities)
                                    <i class="bi bi-chevron-down ms-1 text-muted" style="font-size:.65rem"></i>
                                    @endif
                                </td>
                                <td class="small">{{ $exp->job_title }}</td>
                                <td class="small text-muted">{{ \Carbon\Carbon::parse($exp->start_date)->format('M Y') }}</td>
                                <td class="small text-muted">
                                    @if($exp->end_date)
                                        {{ \Carbon\Carbon::parse($exp->end_date)->format('M Y') }}
                                    @else
                                        <span class="badge bg-success rounded-pill">Current</span>
                                    @endif
                                </td>
                                <td onclick="event.stopPropagation()">
                                    <form action="{{ route('candidate.profile.experience.destroy', $exp) }}" method="POST"
                                          onsubmit="return confirm('Delete this experience record?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @if($exp->responsibilities)
                            <tr id="resp-{{ $exp->id }}" class="d-none">
                                <td colspan="5" class="px-4 pb-3 pt-0">
                                    <div class="p-3 rounded-3" style="background:#f8f9fa;border-left:3px solid #0F1E43">
                                        <div class="small fw-semibold mb-2 text-muted" style="font-size:.7rem;letter-spacing:.05em;text-transform:uppercase">Responsibilities</div>
                                        @php
                                            // Strip certifications / referees bleed-through
                                            $respText = preg_replace(
                                                '/\b(?:CERTIFICATIONS?(?:\s*[&]\s*PROFESSIONAL\s+REGISTRATION)?|REFEREES?|REFERENCES?|TECHNICAL\s+SKILLS?)\b.*/si',
                                                '', $exp->responsibilities
                                            );
                                            $respText = trim($respText);
                                            // Split: prefer newlines, fall back to ". Capital" sentence boundaries
                                            if (str_contains($respText, "\n")) {
                                                $respLines = array_filter(array_map('trim', explode("\n", $respText)));
                                            } else {
                                                $respLines = preg_split('/(?<=[.!?])\s+(?=[A-Z\d])/', $respText) ?: [$respText];
                                                $respLines = array_filter(array_map('trim', $respLines));
                                            }
                                        @endphp
                                        <ul class="mb-0 ps-3" style="font-size:.82rem;line-height:1.6;color:#333">
                                            @foreach($respLines as $pt)
                                            <li>{{ ltrim(trim($pt), '• ') }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        {{-- Add Experience Form --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0"><i class="bi bi-plus-lg me-2"></i>Add Work Experience</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('candidate.profile.experience.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Employer <span class="text-danger">*</span></label>
                            <input type="text" name="employer"
                                   class="form-control @error('employer') is-invalid @enderror"
                                   value="{{ old('employer') }}" placeholder="e.g. ABC Security Ltd" required>
                            @error('employer')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Job Title <span class="text-danger">*</span></label>
                            <input type="text" name="job_title"
                                   class="form-control @error('job_title') is-invalid @enderror"
                                   value="{{ old('job_title') }}" placeholder="e.g. Security Supervisor" required>
                            @error('job_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date"
                                   class="form-control @error('start_date') is-invalid @enderror"
                                   value="{{ old('start_date') }}" required>
                            @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">End Date <span class="text-muted fw-normal">(leave blank if current job)</span></label>
                            <input type="date" name="end_date"
                                   class="form-control @error('end_date') is-invalid @enderror"
                                   value="{{ old('end_date') }}">
                            @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Key Responsibilities <span class="text-muted fw-normal">(optional)</span></label>
                            <textarea name="responsibilities" rows="4"
                                      class="form-control @error('responsibilities') is-invalid @enderror"
                                      placeholder="Briefly describe your key duties and achievements in this role...">{{ old('responsibilities') }}</textarea>
                            @error('responsibilities')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn fw-semibold px-5 py-2" style="background:var(--kk-blue,#0D47A1);color:#fff">
                                <i class="bi bi-plus-circle-fill me-2"></i>Add Experience
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>{{-- /.tab-content --}}

@endsection
