@extends('layouts.admin')
@section('title', 'Edit Job — Admin')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.snow.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/tom-select/2.3.1/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<style>.ts-wrapper.form-select{padding:0}.ts-control{border-radius:.375rem}</style>
@endpush

@section('breadcrumb')
<h1 class="app-content-title">Edit Job: {{ $job->title }}</h1>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.jobs.index') }}">Jobs</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
</nav>
@endsection

@section('content')

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show mb-4">
    <strong>Please fix the following errors:</strong>
    <ul class="mb-0 mt-2 ps-3">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-4">
        <form action="{{ route('admin.jobs.update', $job) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-4">
                {{-- LEFT COLUMN --}}
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Job Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $job->title) }}"
                               class="form-control @error('title') is-invalid @enderror" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    @php
                        $curDept = old('department', $job->department);
                        $selDisc = (array) old('discipline', \App\Services\DisciplineService::jobDisciplines($job->discipline));
                    @endphp
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Department <span class="text-danger">*</span></label>
                        <select name="department" id="departmentSelect" class="form-select @error('department') is-invalid @enderror" required>
                            <option value="">— Select or type a department —</option>
                            @foreach(\App\Models\Job::DEPARTMENTS as $dept)
                            <option value="{{ $dept }}" {{ $curDept === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                            @endforeach
                            @if($curDept && !in_array($curDept, \App\Models\Job::DEPARTMENTS))
                            <option value="{{ $curDept }}" selected>{{ $curDept }}</option>
                            @endif
                        </select>
                        <div class="form-text">Pick from the list, or type to add a new department.</div>
                        @error('department')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Required Discipline(s)</label>
                        <select name="discipline[]" id="disciplineSelect" multiple
                                class="form-select @error('discipline') is-invalid @enderror @error('discipline.*') is-invalid @enderror"
                                placeholder="Search and select one or more disciplines...">
                            @foreach($disciplines as $key => $label)
                                @continue($key === 'any')
                                <option value="{{ $key }}" {{ in_array($key, $selDisc) ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">Select one or more disciplines candidates must have (search or scroll). <strong>Leave empty</strong> = open to all graduates.</div>
                        @error('discipline')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Location <span class="text-danger">*</span></label>
                        @php $curLoc = old('location', $job->location); @endphp
                        <select name="location" id="locationSelect" class="form-select @error('location') is-invalid @enderror" required>
                            <option value="">— Select or type a region/location —</option>
                            @foreach(\App\Models\Job::TZ_REGIONS as $region)
                            <option value="{{ $region }}" {{ $curLoc === $region ? 'selected' : '' }}>{{ $region }}</option>
                            @endforeach
                            @if($curLoc && !in_array($curLoc, \App\Models\Job::TZ_REGIONS))
                            <option value="{{ $curLoc }}" selected>{{ $curLoc }}</option>
                            @endif
                        </select>
                        <div class="form-text">Pick a Tanzania region, or type a more specific location.</div>
                        @error('location')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Number of Vacancies <span class="text-danger">*</span></label>
                        <input type="number" name="vacancies" value="{{ old('vacancies', $job->vacancies) }}"
                               class="form-control @error('vacancies') is-invalid @enderror" min="1" required>
                        @error('vacancies')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Employment Type <span class="text-danger">*</span></label>
                        <select name="employment_type" class="form-select @error('employment_type') is-invalid @enderror" required>
                            @foreach(\App\Models\Job::EMPLOYMENT_TYPES as $key => $label)
                            <option value="{{ $key }}" {{ old('employment_type', $job->employment_type ?? 'full_time') === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('employment_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- RIGHT COLUMN --}}
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Job Posted By <span class="text-danger">*</span></label>
                        <select name="job_type" id="job_type" class="form-select @error('job_type') is-invalid @enderror" required>
                            <option value="kikosi_kazi" {{ old('job_type', $job->job_type) === 'kikosi_kazi' ? 'selected' : '' }}>
                                Kikosi Kazi (Internal Vacancy)
                            </option>
                            <option value="client" {{ old('job_type', $job->job_type) === 'client' ? 'selected' : '' }}>
                                Client Company (HR Placement)
                            </option>
                        </select>
                        @error('job_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3" id="client_name_wrap" style="{{ old('job_type', $job->job_type) === 'client' ? '' : 'display:none' }}">
                        <label class="form-label fw-semibold">Client Company Name <span class="text-danger">*</span></label>
                        <input type="text" name="client_name" value="{{ old('client_name', $job->client_name) }}"
                               class="form-control @error('client_name') is-invalid @enderror"
                               placeholder="e.g. Azam Group, NMB Bank, CRDB...">
                        @error('client_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Application Deadline <span class="text-danger">*</span></label>
                        <input type="date" name="deadline"
                               value="{{ old('deadline', $job->deadline->format('Y-m-d')) }}"
                               class="form-control @error('deadline') is-invalid @enderror" required>
                        @error('deadline')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="draft"    {{ old('status',$job->status) === 'draft'    ? 'selected' : '' }}>Draft</option>
                            <option value="active"   {{ old('status',$job->status) === 'active'   ? 'selected' : '' }}>Active</option>
                            <option value="closed"   {{ old('status',$job->status) === 'closed'   ? 'selected' : '' }}>Closed</option>
                            <option value="archived" {{ old('status',$job->status) === 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Min Salary (TZS)</label>
                            <input type="number" name="salary_min" value="{{ old('salary_min', $job->salary_min) }}"
                                   class="form-control @error('salary_min') is-invalid @enderror" min="0">
                            @error('salary_min')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Max Salary (TZS)</label>
                            <input type="number" name="salary_max" value="{{ old('salary_max', $job->salary_max) }}"
                                   class="form-control @error('salary_max') is-invalid @enderror" min="0">
                            @error('salary_max')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                {{-- FULL WIDTH: Description --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">Job Description <span class="text-danger">*</span></label>
                    <textarea name="description" id="descriptionInput" class="d-none @error('description') is-invalid @enderror">{{ old('description', $job->description) }}</textarea>
                    <div class="kk-quill-wrap"><div id="descriptionEditor"></div></div>
                    @error('description')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                {{-- FULL WIDTH: Requirements --}}
                <div class="col-12">
                    <label class="form-label fw-semibold">Requirements <span class="text-danger">*</span></label>
                    <textarea name="requirements" id="requirementsInput" class="d-none @error('requirements') is-invalid @enderror">{{ old('requirements', $job->requirements) }}</textarea>
                    <div class="kk-quill-wrap"><div id="requirementsEditor"></div></div>
                    @error('requirements')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                {{-- BUTTONS --}}
                <div class="col-12 d-flex gap-3">
                    <button type="submit" class="btn btn-primary fw-semibold px-5">
                        <i class="bi bi-save-fill me-2"></i>Update Job
                    </button>
                    <a href="{{ route('admin.jobs.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                    <a href="{{ route('careers.show', $job) }}" target="_blank" class="btn btn-outline-info px-4 ms-auto">
                        <i class="bi bi-eye-fill me-1"></i>Preview Job
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.min.js"></script>
<script>
const toolbarOptions = [
    ['bold', 'italic', 'underline'],
    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
    ['link', 'clean']
];

// ── Safe init — never let Quill build a second toolbar on the same div ──────
function kkInitQuill(selector, placeholder) {
    const el = document.querySelector(selector);
    if (!el) return null;
    if (el.classList.contains('ql-container') ||
        (el.previousElementSibling && el.previousElementSibling.classList.contains('ql-toolbar'))) {
        return null;
    }
    return new Quill(selector, { theme: 'snow', modules: { toolbar: toolbarOptions }, placeholder });
}

const descInput  = document.getElementById('descriptionInput');
const descEditor = kkInitQuill('#descriptionEditor', 'Write a detailed job description...');
if (descEditor && descInput.value.trim()) {
    descEditor.root.innerHTML = descInput.value;
}

const reqInput  = document.getElementById('requirementsInput');
const reqEditor = kkInitQuill('#requirementsEditor', 'List the qualifications, skills and experience required...');
if (reqEditor && reqInput.value.trim()) {
    reqEditor.root.innerHTML = reqInput.value;
}

// ── Keep the hidden fields in sync as the user types ────────────────────────
function kkSync(editor, input) {
    if (!editor) return false;
    const hasText = editor.getText().trim().length > 0;
    input.value = hasText ? editor.root.innerHTML : '';
    return hasText;
}
if (descEditor) descEditor.on('text-change', function () { kkSync(descEditor, descInput); });
if (reqEditor)  reqEditor.on('text-change',  function () { kkSync(reqEditor,  reqInput);  });

document.querySelector('form').addEventListener('submit', function (e) {
    const descOk = kkSync(descEditor, descInput);
    const reqOk  = kkSync(reqEditor,  reqInput);
    if (descInput.nextElementSibling) descInput.nextElementSibling.style.border = descOk ? '' : '1px solid #dc3545';
    if (reqInput.nextElementSibling)  reqInput.nextElementSibling.style.border  = reqOk  ? '' : '1px solid #dc3545';
    if (!descOk || !reqOk) {
        e.preventDefault();
        const target = !descOk ? descInput.nextElementSibling : reqInput.nextElementSibling;
        if (target) target.scrollIntoView({ behavior: 'smooth', block: 'center' });
        (!descOk ? descEditor : reqEditor).focus();
    }
});

document.getElementById('job_type').addEventListener('change', function () {
    document.getElementById('client_name_wrap').style.display = this.value === 'client' ? '' : 'none';
});
</script>

<style>
.kk-quill-wrap { border: 1px solid #dee2e6; border-radius: .375rem; overflow: hidden; background: #fff; }
.kk-quill-wrap .ql-toolbar.ql-snow  { border: none; border-bottom: 1px solid #dee2e6; background: #f8f9fa; padding: 6px 8px; }
.kk-quill-wrap .ql-container.ql-snow { border: none; font-size: .95rem; }
#descriptionEditor  .ql-editor { min-height: 180px; }
#requirementsEditor .ql-editor { min-height: 130px; }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tom-select/2.3.1/js/tom-select.complete.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (!window.TomSelect) return;
    new TomSelect('#departmentSelect', {
        create: true, createOnBlur: true,
        sortField: { field: 'text', direction: 'asc' },
    });
    new TomSelect('#locationSelect', {
        create: true, createOnBlur: true,
        sortField: { field: 'text', direction: 'asc' },
    });
    new TomSelect('#disciplineSelect', {
        plugins: ['remove_button'], hidePlaceholder: false,
    });
});
</script>

@endsection
