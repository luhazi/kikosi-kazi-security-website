@extends('layouts.candidate')
@section('title', 'My Documents')
@section('page-title', 'My Documents')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color:var(--kk-blue)">My Documents</h3>
        <p class="text-muted mb-0 small">Upload and review your supporting documents.</p>
    </div>
</div>

{{-- UPLOAD CARD --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white border-0 pt-4 px-4">
        <h5 class="fw-bold mb-0" style="color:var(--kk-blue)"><i class="bi bi-cloud-upload-fill me-2"></i>Upload Document</h5>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('candidate.documents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Document Type <span class="text-danger">*</span></label>
                    <select name="file_type" class="form-select @error('file_type') is-invalid @enderror" required>
                        <option value="">-- Select Type --</option>
                        <option value="cv"             {{ old('file_type') === 'cv'             ? 'selected' : '' }}>CV / Resume</option>
                        <option value="national_id"    {{ old('file_type') === 'national_id'    ? 'selected' : '' }}>National ID</option>
                        <option value="certificate"    {{ old('file_type') === 'certificate'    ? 'selected' : '' }}>Certificate / Diploma</option>
                        <option value="passport_photo" {{ old('file_type') === 'passport_photo' ? 'selected' : '' }}>Passport Photo</option>
                        <option value="other"          {{ old('file_type') === 'other'          ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('file_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-5">
                    <label class="form-label fw-semibold small">Choose File <span class="text-danger">*</span></label>
                    <input type="file" name="file" id="fileInput"
                           class="form-control @error('file') is-invalid @enderror"
                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                    @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text text-muted mt-1">
                        <i class="bi bi-info-circle-fill me-1"></i>Max 5 MB &bull; PDF, DOC, DOCX, JPG, PNG
                    </div>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn w-100 fw-semibold"
                            style="background:var(--kk-blue);color:#fff;border-radius:8px">
                        <i class="bi bi-cloud-upload-fill me-2"></i>Upload
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- DOCUMENTS LIST --}}
@php
$typeIcons = [
    'cv'            => ['icon' => 'bi-file-earmark-pdf-fill', 'colour' => '#DC2626', 'bg' => '#FEF2F2'],
    'national_id'   => ['icon' => 'bi-card-text',              'colour' => '#2563EB', 'bg' => '#EFF6FF'],
    'certificate'   => ['icon' => 'bi-award-fill',             'colour' => '#D97706', 'bg' => '#FFFBEB'],
    'passport_photo'=> ['icon' => 'bi-image-fill',             'colour' => '#7C3AED', 'bg' => '#F5F3FF'],
    'other'         => ['icon' => 'bi-file-earmark-fill',      'colour' => '#6B7280', 'bg' => '#F9FAFB'],
];
$typeLabels = [
    'cv'            => 'CV / Resume',
    'national_id'   => 'National ID',
    'certificate'   => 'Certificate / Diploma',
    'passport_photo'=> 'Passport Photo',
    'other'         => 'Other Document',
];
$imageMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
@endphp

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-0 pt-4 px-4 d-flex align-items-center justify-content-between">
        <h5 class="fw-bold mb-0" style="color:var(--kk-blue)">Uploaded Documents</h5>
        <span class="badge rounded-pill" style="background:var(--kk-blue);color:#fff;font-size:.78rem">
            {{ $documents->flatten()->count() }} file{{ $documents->flatten()->count() !== 1 ? 's' : '' }}
        </span>
    </div>
    <div class="card-body p-0">

        @forelse($documents->flatten() as $doc)
        @php
            $meta     = $typeIcons[$doc->file_type] ?? $typeIcons['other'];
            $label    = $typeLabels[$doc->file_type] ?? ucfirst($doc->file_type);
            $isImage  = in_array($doc->mime_type, $imageMimes);
            $isPdf    = $doc->mime_type === 'application/pdf';
            $isWord   = in_array($doc->mime_type, ['application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document']);
            $sizeKb   = $doc->file_size ? round($doc->file_size / 1024, 1) . ' KB' : '';
            $previewUrl  = route('candidate.documents.preview', $doc);
            $downloadUrl = route('candidate.documents.download', $doc);
        @endphp

        <div class="doc-row d-flex align-items-center gap-3 px-4 py-3 border-bottom">
            {{-- Icon badge --}}
            <div style="width:46px;height:46px;border-radius:12px;background:{{ $meta['bg'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="bi {{ $meta['icon'] }}" style="font-size:1.3rem;color:{{ $meta['colour'] }}"></i>
            </div>

            {{-- File info --}}
            <div class="flex-fill" style="min-width:0">
                <div class="fw-semibold small text-truncate" style="color:var(--kk-blue)">{{ $label }}</div>
                <div class="text-muted" style="font-size:.78rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                    {{ $doc->original_name ?? basename($doc->file_path) }}
                </div>
            </div>

            {{-- Meta chips --}}
            <div class="d-none d-md-flex flex-column align-items-end gap-1" style="flex-shrink:0">
                @if($sizeKb)
                <span class="badge rounded-pill" style="background:#F3F4F6;color:#374151;font-size:.72rem;font-weight:500">
                    {{ $sizeKb }}
                </span>
                @endif
                <span class="text-muted" style="font-size:.72rem">{{ $doc->created_at->format('d M Y') }}</span>
            </div>

            {{-- Action buttons --}}
            <div class="d-flex gap-2 align-items-center flex-wrap" style="flex-shrink:0">

                {{-- VIEW --}}
                @if($isImage)
                <button type="button"
                        class="btn btn-sm fw-semibold d-flex align-items-center gap-1"
                        style="background:#EFF6FF;color:#2563EB;border:none;border-radius:8px;padding:.35rem .75rem"
                        onclick="kkPreviewImage('{{ $previewUrl }}', '{{ $label }}', '{{ $doc->original_name ?? basename($doc->file_path) }}')">
                    <i class="bi bi-eye-fill"></i><span class="d-none d-sm-inline">View</span>
                </button>
                @else
                <a href="{{ $previewUrl }}" target="_blank"
                   class="btn btn-sm fw-semibold d-flex align-items-center gap-1"
                   style="background:#EFF6FF;color:#2563EB;border:none;border-radius:8px;padding:.35rem .75rem">
                    <i class="bi bi-eye-fill"></i><span class="d-none d-sm-inline">View</span>
                </a>
                @endif

                {{-- DOWNLOAD --}}
                <a href="{{ $downloadUrl }}"
                   class="btn btn-sm d-flex align-items-center gap-1"
                   style="background:#F0FDF4;color:#15803D;border:none;border-radius:8px;padding:.35rem .75rem">
                    <i class="bi bi-download"></i><span class="d-none d-sm-inline">Download</span>
                </a>

                {{-- SMART IMPORT — CV only --}}
                @if($doc->file_type === 'cv')
                <button type="button"
                        class="btn btn-sm d-flex align-items-center gap-1"
                        style="background:#EDE9FE;color:#4C1D95;border:none;border-radius:8px;padding:.35rem .75rem"
                        onclick="kkParseCv({{ $doc->id }})"
                        title="Extract data and fill your profile">
                    <i class="bi bi-magic"></i><span class="d-none d-sm-inline">Import</span>
                </button>
                @endif

                {{-- DELETE --}}
                <form action="{{ route('candidate.documents.destroy', $doc) }}" method="POST"
                      onsubmit="return confirm('Delete this document permanently?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="btn btn-sm d-flex align-items-center gap-1"
                            style="background:#FEF2F2;color:#DC2626;border:none;border-radius:8px;padding:.35rem .75rem">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="text-center py-5 text-muted">
            <div style="width:72px;height:72px;background:#F1F4F9;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
                <i class="bi bi-folder2-open" style="font-size:2rem;color:var(--kk-blue);opacity:.3"></i>
            </div>
            <p class="mb-0">No documents uploaded yet. Use the form above to upload your first document.</p>
        </div>
        @endforelse
    </div>
</div>

{{-- ================================================================ --}}
{{-- SMART CV IMPORT MODAL                                            --}}
{{-- ================================================================ --}}
<div class="modal fade" id="cvImportModal" tabindex="-1" aria-hidden="true"
     data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 overflow-hidden shadow-lg">

            <div class="modal-header border-0 px-4 py-3" style="background:#3B1F6E">
                <div>
                    <h6 class="modal-title text-white fw-bold mb-0">
                        <i class="bi bi-magic me-2"></i>Smart CV Import
                    </h6>
                    <div class="text-white-50 small">Review extracted data — edit anything wrong before applying</div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-0" style="background:#F1F4F9;min-height:240px">

                {{-- LOADING --}}
                <div id="cvImportLoading" class="text-center py-5 px-4">
                    <div class="spinner-border mb-3" style="color:#3B1F6E;width:3rem;height:3rem" role="status">
                        <span class="visually-hidden">Loading…</span>
                    </div>
                    <p class="fw-semibold mb-1" style="color:var(--kk-blue)">Analysing your CV…</p>
                    <p class="text-muted small mb-0">Extracting text and identifying sections</p>
                </div>

                {{-- ERROR --}}
                <div id="cvImportError" class="d-none p-4">
                    <div class="alert alert-danger d-flex align-items-start gap-3 rounded-3 mb-0 border-0 shadow-sm">
                        <i class="bi bi-exclamation-triangle-fill fs-4 flex-shrink-0 mt-1"></i>
                        <div>
                            <div class="fw-bold mb-1">Could not parse this CV</div>
                            <div id="cvImportErrorMsg" class="small"></div>
                        </div>
                    </div>
                </div>

                {{-- RESULT --}}
                <form id="cvImportForm" method="POST" action="{{ route('candidate.cv.import') }}" class="d-none p-4">
                    @csrf
                    <div class="alert border-0 rounded-3 mb-4 d-flex align-items-center gap-3 shadow-sm"
                         style="background:#EDE9FE;color:#3B1F6E">
                        <i class="bi bi-check-circle-fill fs-5 flex-shrink-0"></i>
                        <div class="small">
                            <strong>CV parsed successfully!</strong>
                            Edit anything that looks wrong, then click <strong>Apply to Profile</strong>.
                            Only non-empty fields will be updated.
                        </div>
                    </div>

                    {{-- Personal Info --}}
                    <div class="card border-0 shadow-sm rounded-3 mb-3">
                        <div class="card-header bg-white border-0 py-3 px-4 fw-bold" style="color:var(--kk-blue)">
                            <i class="bi bi-person-fill me-2" style="color:#3B1F6E"></i>Personal Information
                        </div>
                        <div class="card-body px-4 pb-4 pt-2">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Phone Number</label>
                                    <input type="text" name="phone" id="imp_phone" class="form-control rounded-3"
                                           placeholder="e.g. +255712345678">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Email <span class="text-muted fw-normal">(from CV, display only)</span></label>
                                    <input type="text" id="imp_email" class="form-control rounded-3 bg-light text-muted" readonly>
                                    <div class="form-text">Email is managed in account settings.</div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">City</label>
                                    <input type="text" name="city" id="imp_city" class="form-control rounded-3">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">Region</label>
                                    <input type="text" name="region" id="imp_region" class="form-control rounded-3">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">Nationality</label>
                                    <input type="text" name="nationality" id="imp_nationality" class="form-control rounded-3">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-semibold">Address</label>
                                    <input type="text" name="address" id="imp_address" class="form-control rounded-3">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-semibold">Professional Summary / Bio</label>
                                    <textarea name="bio" id="imp_bio" class="form-control rounded-3" rows="3"
                                              placeholder="A brief introduction about yourself…"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Education --}}
                    <div class="card border-0 shadow-sm rounded-3 mb-3">
                        <div class="card-header bg-white border-0 py-3 px-4 d-flex align-items-center justify-content-between">
                            <span class="fw-bold" style="color:var(--kk-blue)">
                                <i class="bi bi-mortarboard-fill me-2" style="color:#3B1F6E"></i>Education
                            </span>
                            <span id="eduCountBadge" class="badge rounded-pill"
                                  style="background:#3B1F6E;color:#fff;font-size:.75rem">0 found</span>
                        </div>
                        <div class="card-body px-4 pb-4 pt-2">
                            <p class="text-muted small mb-3">These will be <strong>added</strong> to your Education history. Existing records are not removed.</p>
                            <div id="eduRows"></div>
                            <button type="button" onclick="kkAddEduRow({})"
                                    class="btn btn-sm mt-1 fw-semibold"
                                    style="background:#EDE9FE;color:#4C1D95;border:none;border-radius:8px;padding:.35rem .9rem">
                                <i class="bi bi-plus-circle-fill me-1"></i>Add Entry
                            </button>
                        </div>
                    </div>

                    {{-- Experience --}}
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white border-0 py-3 px-4 d-flex align-items-center justify-content-between">
                            <span class="fw-bold" style="color:var(--kk-blue)">
                                <i class="bi bi-briefcase-fill me-2" style="color:#3B1F6E"></i>Work Experience
                            </span>
                            <span id="expCountBadge" class="badge rounded-pill"
                                  style="background:#3B1F6E;color:#fff;font-size:.75rem">0 found</span>
                        </div>
                        <div class="card-body px-4 pb-4 pt-2">
                            <p class="text-muted small mb-3">These will be <strong>added</strong> to your Experience history. Existing records are not removed.</p>
                            <div id="expRows"></div>
                            <button type="button" onclick="kkAddExpRow({})"
                                    class="btn btn-sm mt-1 fw-semibold"
                                    style="background:#EDE9FE;color:#4C1D95;border:none;border-radius:8px;padding:.35rem .9rem">
                                <i class="bi bi-plus-circle-fill me-1"></i>Add Entry
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer border-0 bg-white px-4 py-3 justify-content-between d-none"
                 id="cvImportFooter">
                <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="cvImportForm" class="btn fw-semibold px-4 rounded-3"
                        style="background:#3B1F6E;color:#fff">
                    <i class="bi bi-check2-circle me-2"></i>Apply to Profile
                </button>
            </div>
        </div>
    </div>
</div>

{{-- IMAGE PREVIEW MODAL --}}
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 overflow-hidden shadow-lg">
            <div class="modal-header border-0 px-4 py-3" style="background:var(--kk-blue)">
                <div>
                    <h6 class="modal-title text-white fw-bold mb-0" id="imgModalTitle">Document Preview</h6>
                    <div class="text-white-50 small" id="imgModalSubtitle"></div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0 text-center bg-dark" style="min-height:200px;position:relative">
                <div id="imgModalSpinner"
                     style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center">
                    <div class="spinner-border text-light" role="status"><span class="visually-hidden">Loading…</span></div>
                </div>
                <img id="imgModalSrc" src="" alt="Document Preview"
                     style="max-width:100%;max-height:80vh;display:none;object-fit:contain"
                     onload="document.getElementById('imgModalSpinner').style.display='none';this.style.display='block'">
            </div>
            <div class="modal-footer border-0 px-4 py-3 bg-white justify-content-between">
                <span class="text-muted small" id="imgModalMeta"></span>
                <a id="imgModalDownload" href="#" class="btn btn-sm fw-semibold"
                   style="background:var(--kk-blue);color:#fff;border-radius:8px">
                    <i class="bi bi-download me-1"></i>Download
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ============================================================
// CV SMART IMPORT
// ============================================================
var kkEduIdx = 0, kkExpIdx = 0;

function kkEsc(s) {
    return String(s == null ? '' : s)
        .replace(/&/g, '&amp;').replace(/"/g, '&quot;')
        .replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

function kkSetImportState(state, errorMsg) {
    document.getElementById('cvImportLoading').classList.toggle('d-none', state !== 'loading');
    document.getElementById('cvImportError').classList.toggle('d-none',   state !== 'error');
    document.getElementById('cvImportForm').classList.toggle('d-none',    state !== 'result');
    document.getElementById('cvImportFooter').classList.toggle('d-none',  state !== 'result');
    if (state === 'error' && errorMsg) {
        document.getElementById('cvImportErrorMsg').textContent = errorMsg;
    }
}

function kkAddEduRow(data) {
    var i     = kkEduIdx++;
    var inst  = data.institution    || '';
    var qual  = data.qualification  || '';
    var field = data.field_of_study || '';
    var year  = data.year_completed || '';
    var grade = data.grade          || '';
    var html =
        '<div class="border rounded-3 p-3 mb-2 bg-white edu-row position-relative">' +
        '<button type="button" onclick="this.closest(\'.edu-row\').remove()" class="btn btn-sm position-absolute" ' +
            'style="top:8px;right:8px;background:#FEF2F2;color:#DC2626;border:none;border-radius:6px;padding:.2rem .5rem;font-size:.75rem">' +
            '<i class="bi bi-x-lg"></i></button>' +
        '<div class="row g-2 pe-4">' +
            '<div class="col-md-6"><label class="form-label small fw-semibold mb-1">Institution <span class="text-danger">*</span></label>' +
            '<input type="text" name="edu[' + i + '][institution]" class="form-control form-control-sm rounded-2" value="' + kkEsc(inst) + '" placeholder="e.g. University of Dar es Salaam" required></div>' +
            '<div class="col-md-6"><label class="form-label small fw-semibold mb-1">Qualification <span class="text-danger">*</span></label>' +
            '<input type="text" name="edu[' + i + '][qualification]" class="form-control form-control-sm rounded-2" value="' + kkEsc(qual) + '" placeholder="e.g. Bachelor of Science" required></div>' +
            '<div class="col-md-5"><label class="form-label small fw-semibold mb-1">Field of Study <span class="text-danger">*</span></label>' +
            '<input type="text" name="edu[' + i + '][field]" class="form-control form-control-sm rounded-2" value="' + kkEsc(field) + '" placeholder="e.g. Computer Science" required></div>' +
            '<div class="col-md-4"><label class="form-label small fw-semibold mb-1">Year <span class="text-danger">*</span></label>' +
            '<input type="number" name="edu[' + i + '][year]" class="form-control form-control-sm rounded-2" min="1950" max="2099" value="' + kkEsc(year) + '" required></div>' +
            '<div class="col-md-3"><label class="form-label small fw-semibold mb-1">Grade / GPA</label>' +
            '<input type="text" name="edu[' + i + '][grade]" class="form-control form-control-sm rounded-2" value="' + kkEsc(grade) + '" placeholder="e.g. 3.8"></div>' +
        '</div></div>';
    document.getElementById('eduRows').insertAdjacentHTML('beforeend', html);
}

function kkAddExpRow(data) {
    var i     = kkExpIdx++;
    var emp   = data.employer         || '';
    var title = data.job_title        || '';
    var start = data.start_date       || '';
    var end   = data.end_date         || '';
    var cur   = data.is_current       || false;
    // Format responsibilities: each "• sentence" on its own line
    var rawDesc = (data.responsibilities || '').trim();
    var desc;
    if (rawDesc.includes('\n') || rawDesc.includes('\u2022')) {
        // Already bullet-formatted — just normalise line breaks
        desc = rawDesc.replace(/\r?\n/g, '\n').replace(/^[•\u00B7\u25AA\s]+/gm, '').trim();
    } else {
        // Split plain text on ". CAPITAL" boundaries
        desc = rawDesc.replace(/\.\s+(?=[A-Z])/g, '.\n').trim();
    }
    var dis   = cur ? ' disabled' : '';
    var chk   = cur ? ' checked'  : '';
    var html =
        '<div class="border rounded-3 p-3 mb-2 bg-white exp-row position-relative">' +
        '<button type="button" onclick="this.closest(\'.exp-row\').remove()" class="btn btn-sm position-absolute" ' +
            'style="top:8px;right:8px;background:#FEF2F2;color:#DC2626;border:none;border-radius:6px;padding:.2rem .5rem;font-size:.75rem">' +
            '<i class="bi bi-x-lg"></i></button>' +
        '<div class="row g-2 pe-4">' +
            '<div class="col-md-6"><label class="form-label small fw-semibold mb-1">Job Title <span class="text-danger">*</span></label>' +
            '<input type="text" name="exp[' + i + '][job_title]" class="form-control form-control-sm rounded-2" value="' + kkEsc(title) + '" required></div>' +
            '<div class="col-md-6"><label class="form-label small fw-semibold mb-1">Employer <span class="text-danger">*</span></label>' +
            '<input type="text" name="exp[' + i + '][employer]" class="form-control form-control-sm rounded-2" value="' + kkEsc(emp) + '" required></div>' +
            '<div class="col-md-4"><label class="form-label small fw-semibold mb-1">Start Date <span class="text-danger">*</span></label>' +
            '<input type="text" name="exp[' + i + '][start_date]" class="form-control form-control-sm rounded-2" value="' + kkEsc(start) + '" placeholder="e.g. Jan 2020" required></div>' +
            '<div class="col-md-4"><label class="form-label small fw-semibold mb-1">End Date</label>' +
            '<input type="text" name="exp[' + i + '][end_date]" id="expEnd' + i + '" class="form-control form-control-sm rounded-2" value="' + kkEsc(end) + '" placeholder="e.g. Dec 2023"' + dis + '></div>' +
            '<div class="col-md-4 d-flex align-items-end pb-1">' +
                '<div class="form-check ms-1"><input type="checkbox" name="exp[' + i + '][is_current]" value="1" id="expCur' + i + '" class="form-check-input"' + chk +
                ' onchange="var e=document.getElementById(\'expEnd' + i + '\');e.disabled=this.checked;if(this.checked)e.value=\'\';">' +
                '<label for="expCur' + i + '" class="form-check-label small">Currently working here</label></div></div>' +
            '<div class="col-12"><label class="form-label small fw-semibold mb-1">Responsibilities</label>' +
            '<textarea name="exp[' + i + '][responsibilities]" class="form-control form-control-sm rounded-2" rows="2" placeholder="Key duties and achievements…">' + kkEsc(desc) + '</textarea></div>' +
        '</div></div>';
    document.getElementById('expRows').insertAdjacentHTML('beforeend', html);
}

async function kkParseCv(documentId) {
    kkEduIdx = 0; kkExpIdx = 0;
    document.getElementById('eduRows').innerHTML = '';
    document.getElementById('expRows').innerHTML = '';
    ['imp_phone','imp_email','imp_city','imp_region','imp_nationality','imp_address','imp_bio']
        .forEach(function(id){ var el=document.getElementById(id); if(el) el.value=''; });

    kkSetImportState('loading');
    var modal = new bootstrap.Modal(document.getElementById('cvImportModal'));
    modal.show();

    try {
        var fd = new FormData();
        fd.append('document_id', documentId);
        fd.append('_token', '{{ csrf_token() }}');

        var resp = await fetch('{{ route("candidate.cv.parse") }}', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: fd,
        });
        var json = await resp.json();

        if (!resp.ok || json.error) {
            kkSetImportState('error', json.error || json.message || 'Failed to parse the CV. Please try again.');
            return;
        }

        var d = json.data;
        document.getElementById('imp_phone').value       = d.phone       || '';
        document.getElementById('imp_email').value       = d.email       || '';
        document.getElementById('imp_city').value        = d.city        || '';
        document.getElementById('imp_region').value      = d.region      || '';
        document.getElementById('imp_nationality').value = d.nationality || '';
        document.getElementById('imp_address').value     = d.address     || '';
        document.getElementById('imp_bio').value         = d.bio         || '';

        var edu = Array.isArray(d.education)  ? d.education  : [];
        var exp = Array.isArray(d.experience) ? d.experience : [];
        edu.forEach(function(e){ kkAddEduRow(e); });
        exp.forEach(function(e){ kkAddExpRow(e); });
        document.getElementById('eduCountBadge').textContent = edu.length + ' found';
        document.getElementById('expCountBadge').textContent = exp.length + ' found';

        kkSetImportState('result');
    } catch(e) {
        kkSetImportState('error', 'Network error: ' + e.message);
    }
}

// ============================================================
// IMAGE PREVIEW
// ============================================================
function kkPreviewImage(previewUrl, label, filename) {
    var img   = document.getElementById('imgModalSrc');
    var spin  = document.getElementById('imgModalSpinner');
    var title = document.getElementById('imgModalTitle');
    var sub   = document.getElementById('imgModalSubtitle');
    var dl    = document.getElementById('imgModalDownload');
    img.style.display = 'none'; img.src = '';
    spin.style.display = 'flex';
    title.textContent = label;
    sub.textContent   = filename;
    dl.href = previewUrl.replace('/preview', '/download');
    img.src = previewUrl;
    new bootstrap.Modal(document.getElementById('imagePreviewModal')).show();
}

// Hover effect on doc rows
document.querySelectorAll('.doc-row').forEach(function(row) {
    row.style.transition = 'background .15s';
    row.addEventListener('mouseenter', function(){ this.style.background='#F8F9FC'; });
    row.addEventListener('mouseleave', function(){ this.style.background=''; });
});
</script>
@endpush
