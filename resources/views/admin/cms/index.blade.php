@extends('layouts.admin')
@section('title', 'Content Management — Admin')

@section('breadcrumb')
<h1 class="app-content-title">Content Management</h1>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">CMS</li>
    </ol>
</nav>
@endsection

@section('content')

{{-- TABS --}}
<ul class="nav nav-tabs mb-4" id="cmsTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="services-tab" data-bs-toggle="tab" data-bs-target="#services-panel"
                type="button" role="tab">
            <i class="bi bi-gear-fill me-1"></i>Services
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="testimonials-tab" data-bs-toggle="tab" data-bs-target="#testimonials-panel"
                type="button" role="tab">
            <i class="bi bi-chat-quote-fill me-1"></i>Testimonials
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="team-tab" data-bs-toggle="tab" data-bs-target="#team-panel"
                type="button" role="tab">
            <i class="bi bi-people-fill me-1"></i>Team Members
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="gallery-tab" data-bs-toggle="tab" data-bs-target="#gallery-panel"
                type="button" role="tab">
            <i class="bi bi-image-fill me-1"></i>Photo Gallery
            <span class="badge bg-secondary ms-1">{{ $galleryPhotos->count() }}</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="clients-tab" data-bs-toggle="tab" data-bs-target="#clients-panel"
                type="button" role="tab">
            <i class="bi bi-building-fill me-1"></i>Clients
            <span class="badge bg-secondary ms-1">{{ $clients->count() }}</span>
        </button>
    </li>
</ul>

<div class="tab-content" id="cmsTabsContent">

    {{-- ============ SERVICES TAB ============ --}}
    <div class="tab-pane fade show active" id="services-panel" role="tabpanel">

        {{-- Services Table --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0">Service Listings</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3">Title</th>
                                <th>Category</th>
                                <th>Icon</th>
                                <th>Sort</th>
                                <th>Active</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($services ?? [] as $service)
                            <tr>
                                <td class="px-4 fw-semibold small">{{ $service->title }}</td>
                                <td class="small text-muted">{{ ucfirst($service->category) }}</td>
                                <td>
                                    <i class="bi bi-{{ $service->displayIcon() }} fs-5" style="color:var(--kk-blue)"></i>
                                    @unless($service->icon)<span class="badge bg-light text-muted ms-1" style="font-size:.62rem">auto</span>@endunless
                                </td>
                                <td class="text-center small">{{ $service->sort_order ?? 0 }}</td>
                                <td>
                                    @if($service->is_active)
                                    <span class="badge bg-success">Active</span>
                                    @else
                                    <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-sm btn-outline-primary btn-edit-service"
                                            data-id="{{ $service->id }}"
                                            data-title="{{ $service->title }}"
                                            data-slug="{{ $service->slug }}"
                                            data-category="{{ $service->category }}"
                                            data-description="{{ $service->description }}"
                                            data-icon="{{ $service->icon }}"
                                            data-sort="{{ $service->sort_order ?? 0 }}"
                                            data-active="{{ $service->is_active ? '1' : '0' }}"
                                            data-url="{{ route('admin.cms.services.update', $service) }}"
                                            data-bs-toggle="modal" data-bs-target="#editServiceModal">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                        <form action="{{ route('admin.cms.services.destroy', $service) }}" method="POST"
                                              onsubmit="return confirm('Delete this service?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted small">No services added yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Add Service Form --}}
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0"><i class="bi bi-plus-lg me-2"></i>Add New Service</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.cms.services.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="svcTitle" value="{{ old('title') }}"
                                   class="form-control form-control-sm @error('title') is-invalid @enderror"
                                   placeholder="e.g. Uniformed Security Guards" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">Slug <span class="text-danger">*</span></label>
                            <input type="text" name="slug" id="svcSlug" value="{{ old('slug') }}"
                                   class="form-control form-control-sm @error('slug') is-invalid @enderror"
                                   placeholder="auto-filled from title" required>
                            @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">Category <span class="text-danger">*</span></label>
                            <select name="category" class="form-select form-select-sm @error('category') is-invalid @enderror" required>
                                <option value="">-- Select --</option>
                                <option value="security"  {{ old('category') === 'security'  ? 'selected' : '' }}>Security &amp; Risk Management</option>
                                <option value="hr"        {{ old('category') === 'hr'        ? 'selected' : '' }}>Human Capital Solutions</option>
                                <option value="insurance" {{ old('category') === 'insurance' ? 'selected' : '' }}>Insurance Advisory &amp; Brokerage</option>
                                <option value="cleaning"  {{ old('category') === 'cleaning'  ? 'selected' : '' }}>Facility Management Services</option>
                            </select>
                            @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Description <span class="text-danger">*</span></label>
                            <textarea name="description" rows="3"
                                      class="form-control form-control-sm @error('description') is-invalid @enderror"
                                      placeholder="Brief description of this service..." required>{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Icon</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><i class="bi bi-{{ old('icon', 'shield-check') }}" id="addIconPreview"></i></span>
                                <select name="icon" id="addIconSelect" class="form-select @error('icon') is-invalid @enderror">
                                    @foreach(\App\Models\Service::ICON_OPTIONS as $ic => $lbl)
                                    <option value="{{ $ic }}" {{ old('icon') === $ic ? 'selected' : '' }}>{{ $lbl }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-text">Pick an icon — the preview updates on the left.</div>
                            @error('icon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Sort Order</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                                   class="form-control form-control-sm @error('sort_order') is-invalid @enderror"
                                   min="0">
                            @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-check mb-0">
                                <input type="checkbox" name="is_active" value="1"
                                       class="form-check-input" id="svcActive"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold small" for="svcActive">Active (visible on site)</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-sm fw-semibold px-4">
                                <i class="bi bi-save-fill me-1"></i>Add Service
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ============ TESTIMONIALS TAB ============ --}}
    <div class="tab-pane fade" id="testimonials-panel" role="tabpanel">

        {{-- Testimonials Table --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0">Client Testimonials</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3">Client Name</th>
                                <th>Company</th>
                                <th>Quote (preview)</th>
                                <th>Sort</th>
                                <th>Active</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($testimonials ?? [] as $t)
                            <tr>
                                <td class="px-4 fw-semibold small">{{ $t->client_name }}</td>
                                <td class="small text-muted">{{ $t->company ?? '—' }}</td>
                                <td class="small text-muted" style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                                    "{{ Str::limit($t->quote, 60) }}"
                                </td>
                                <td class="text-center small">{{ $t->sort_order ?? 0 }}</td>
                                <td>
                                    @if($t->is_active)
                                    <span class="badge bg-success">Active</span>
                                    @else
                                    <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-sm btn-outline-primary btn-edit-testimonial"
                                                data-url="{{ route('admin.cms.testimonials.update', $t) }}"
                                                data-client="{{ $t->client_name }}"
                                                data-company="{{ $t->company }}"
                                                data-quote="{{ $t->quote }}"
                                                data-sort="{{ $t->sort_order }}"
                                                data-active="{{ $t->is_active ? '1' : '0' }}"
                                                data-bs-toggle="modal" data-bs-target="#editTestimonialModal">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                        <form action="{{ route('admin.cms.testimonials.destroy', $t) }}" method="POST"
                                              onsubmit="return confirm('Delete this testimonial?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted small">No testimonials added yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Add Testimonial Form --}}
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0"><i class="bi bi-plus-lg me-2"></i>Add Testimonial</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.cms.testimonials.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Client Name <span class="text-danger">*</span></label>
                            <input type="text" name="client_name" value="{{ old('client_name') }}"
                                   class="form-control form-control-sm @error('client_name') is-invalid @enderror"
                                   placeholder="e.g. John Magufuli" required>
                            @error('client_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Company <span class="text-muted fw-normal">(optional)</span></label>
                            <input type="text" name="company" value="{{ old('company') }}"
                                   class="form-control form-control-sm @error('company') is-invalid @enderror"
                                   placeholder="e.g. ABC Corporation Ltd">
                            @error('company')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Testimonial Quote <span class="text-danger">*</span></label>
                            <textarea name="quote" rows="4"
                                      class="form-control form-control-sm @error('quote') is-invalid @enderror"
                                      placeholder="What did the client say about Kikosi Kazi?" required>{{ old('quote') }}</textarea>
                            @error('quote')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Sort Order</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                                   class="form-control form-control-sm @error('sort_order') is-invalid @enderror" min="0">
                            @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-check mb-0">
                                <input type="checkbox" name="is_active" value="1"
                                       class="form-check-input" id="testActive"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold small" for="testActive">Active (show on homepage)</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-sm fw-semibold px-4">
                                <i class="bi bi-save-fill me-1"></i>Add Testimonial
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ============ TEAM MEMBERS TAB ============ --}}
    <div class="tab-pane fade" id="team-panel" role="tabpanel">

        {{-- Team Members Table --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0">Team Members</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3" style="width:60px">Photo</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Department</th>
                                <th>CEO?</th>
                                <th>Sort</th>
                                <th>Active</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($teamMembers ?? [] as $member)
                            <tr>
                                <td class="px-4">
                                    @if($member->photo_path)
                                    <img src="{{ asset('storage/' . $member->photo_path) }}" alt="{{ $member->name }}"
                                         class="rounded-circle" style="width:40px;height:40px;object-fit:cover">
                                    @else
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center"
                                         style="width:40px;height:40px">
                                        <i class="bi bi-person-fill text-white"></i>
                                    </div>
                                    @endif
                                </td>
                                <td class="fw-semibold small">{{ $member->name }}</td>
                                <td class="small text-muted">{{ $member->role }}</td>
                                <td class="small text-muted">{{ $member->department ?? '—' }}</td>
                                <td>
                                    @if($member->is_ceo)
                                    <span class="badge bg-warning text-dark">CEO</span>
                                    @else
                                    <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td class="text-center small">{{ $member->sort_order }}</td>
                                <td>
                                    @if($member->is_active)
                                    <span class="badge bg-success">Active</span>
                                    @else
                                    <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-sm btn-outline-primary btn-edit-team"
                                            data-id="{{ $member->id }}"
                                            data-name="{{ $member->name }}"
                                            data-role="{{ $member->role }}"
                                            data-department="{{ $member->department }}"
                                            data-bio="{{ $member->bio }}"
                                            data-email="{{ $member->email }}"
                                            data-linkedin="{{ $member->linkedin }}"
                                            data-is-ceo="{{ $member->is_ceo ? '1' : '0' }}"
                                            data-ceo-message="{{ $member->ceo_message }}"
                                            data-sort="{{ $member->sort_order }}"
                                            data-active="{{ $member->is_active ? '1' : '0' }}"
                                            data-url="{{ route('admin.cms.team.update', $member) }}"
                                            data-bs-toggle="modal" data-bs-target="#editTeamModal">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                        <form action="{{ route('admin.cms.team.destroy', $member) }}" method="POST"
                                              onsubmit="return confirm('Delete this team member?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted small">No team members added yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Add Team Member Form --}}
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0"><i class="bi bi-plus-lg me-2"></i>Add Team Member</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.cms.team.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                   class="form-control form-control-sm @error('name') is-invalid @enderror"
                                   placeholder="e.g. John Mwangi" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Job Title / Role <span class="text-danger">*</span></label>
                            <input type="text" name="role" value="{{ old('role') }}"
                                   class="form-control form-control-sm @error('role') is-invalid @enderror"
                                   placeholder="e.g. Security Operations Manager" required>
                            @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Department</label>
                            <input type="text" name="department" value="{{ old('department') }}"
                                   class="form-control form-control-sm @error('department') is-invalid @enderror"
                                   placeholder="e.g. Human Resources">
                            @error('department')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="form-control form-control-sm @error('email') is-invalid @enderror"
                                   placeholder="john@kikosikazi.co.tz">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">LinkedIn URL</label>
                            <input type="url" name="linkedin" value="{{ old('linkedin') }}"
                                   class="form-control form-control-sm @error('linkedin') is-invalid @enderror"
                                   placeholder="https://linkedin.com/in/...">
                            @error('linkedin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Profile Photo</label>
                            <input type="file" name="photo" class="form-control form-control-sm @error('photo') is-invalid @enderror"
                                   accept="image/jpeg,image/png,image/webp">
                            <div class="form-text">JPG/PNG/WebP, max 2MB</div>
                            @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Bio / About</label>
                            <textarea name="bio" rows="3"
                                      class="form-control form-control-sm @error('bio') is-invalid @enderror"
                                      placeholder="Short biography for the about page...">{{ old('bio') }}</textarea>
                            @error('bio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12" id="ceoMessageWrap" style="display:none">
                            <label class="form-label fw-semibold small">CEO Message <span class="text-danger">*</span></label>
                            <textarea name="ceo_message" rows="5"
                                      class="form-control form-control-sm @error('ceo_message') is-invalid @enderror"
                                      placeholder="The CEO's message to appear on the About page...">{{ old('ceo_message') }}</textarea>
                            @error('ceo_message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">Sort Order</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                                   class="form-control form-control-sm" min="0">
                        </div>
                        <div class="col-md-3 d-flex align-items-end gap-3">
                            <div class="form-check mb-0">
                                <input type="checkbox" name="is_ceo" value="1" class="form-check-input" id="isCeo"
                                       {{ old('is_ceo') ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold small" for="isCeo">Is CEO</label>
                            </div>
                            <div class="form-check mb-0">
                                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="teamActive"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold small" for="teamActive">Active</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-sm fw-semibold px-4">
                                <i class="bi bi-save-fill me-1"></i>Add Team Member
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ============ PHOTO GALLERY TAB ============ --}}
    <div class="tab-pane fade" id="gallery-panel" role="tabpanel">
        <div class="row g-4">

            {{-- Upload form --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white border-0 pt-3 px-3">
                        <h6 class="fw-bold mb-0"><i class="bi bi-cloud-upload-fill me-2"></i>Add Photo</h6>
                        <small class="text-muted">Post daily activities to the public gallery.</small>
                    </div>
                    <form action="{{ route('admin.cms.gallery.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body p-3">
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Photo <span class="text-danger">*</span></label>
                                <input type="file" name="image" class="form-control form-control-sm @error('image') is-invalid @enderror"
                                       accept="image/*" required>
                                <div class="form-text">JPG, PNG or WEBP · max 5 MB</div>
                                @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Title / Caption</label>
                                <input type="text" name="title" value="{{ old('title') }}"
                                       class="form-control form-control-sm" placeholder="e.g. Guard deployment at Posta HQ">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Description</label>
                                <textarea name="caption" rows="2" class="form-control form-control-sm"
                                          placeholder="Optional short description">{{ old('caption') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Category</label>
                                <select name="category" class="form-select form-select-sm">
                                    <option value="">— General —</option>
                                    @foreach(\App\Models\GalleryPhoto::CATEGORIES as $key => $label)
                                    <option value="{{ $key }}" {{ old('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col-7">
                                    <label class="form-label fw-semibold small">Date</label>
                                    <input type="date" name="event_date" value="{{ old('event_date') }}" class="form-control form-control-sm">
                                </div>
                                <div class="col-5">
                                    <label class="form-label fw-semibold small">Order</label>
                                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="is_published" value="1" id="gp_pub" checked>
                                <label class="form-check-label small" for="gp_pub">Publish immediately</label>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm w-100 fw-semibold">
                                <i class="bi bi-upload me-1"></i>Upload Photo
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Existing photos --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white border-0 pt-3 px-3 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0"><i class="bi bi-images me-2"></i>Gallery Photos</h6>
                        <a href="{{ route('gallery') }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-eye-fill me-1"></i>View public page
                        </a>
                    </div>
                    <div class="card-body p-3">
                        @forelse($galleryPhotos as $photo)
                        @if($loop->first)<div class="row g-3">@endif
                            <div class="col-md-4 col-6">
                                <div class="border rounded-3 overflow-hidden h-100 d-flex flex-column">
                                    <div style="height:130px;background:#f1f3f7;overflow:hidden">
                                        <img src="{{ asset('storage/' . $photo->image_path) }}" alt="{{ $photo->title }}"
                                             style="width:100%;height:100%;object-fit:cover">
                                    </div>
                                    <div class="p-2 flex-fill d-flex flex-column">
                                        <div class="small fw-semibold text-truncate" title="{{ $photo->title }}">
                                            {{ $photo->title ?: 'Untitled' }}
                                        </div>
                                        <div class="text-muted" style="font-size:.72rem">
                                            {{ $photo->categoryLabel() }}
                                            @if($photo->event_date) · {{ $photo->event_date->format('d M Y') }} @endif
                                        </div>
                                        <div class="mt-2 d-flex align-items-center gap-1">
                                            @if($photo->is_published)
                                            <span class="badge bg-success" style="font-size:.65rem">Published</span>
                                            @else
                                            <span class="badge bg-secondary" style="font-size:.65rem">Hidden</span>
                                            @endif
                                            <div class="ms-auto d-flex gap-1">
                                                <button type="button" class="btn btn-sm btn-outline-primary py-0 px-1 btn-edit-gallery" title="Edit"
                                                        data-url="{{ route('admin.cms.gallery.update', $photo) }}"
                                                        data-title="{{ $photo->title }}"
                                                        data-caption="{{ $photo->caption }}"
                                                        data-category="{{ $photo->category }}"
                                                        data-date="{{ $photo->event_date?->format('Y-m-d') }}"
                                                        data-sort="{{ $photo->sort_order }}"
                                                        data-published="{{ $photo->is_published ? '1' : '0' }}"
                                                        data-bs-toggle="modal" data-bs-target="#editGalleryModal">
                                                    <i class="bi bi-pencil-fill" style="font-size:.75rem"></i>
                                                </button>
                                                <form action="{{ route('admin.cms.gallery.destroy', $photo) }}" method="POST"
                                                      onsubmit="return confirm('Remove this photo from the gallery?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger py-0 px-1" title="Delete">
                                                        <i class="bi bi-trash-fill" style="font-size:.75rem"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @if($loop->last)</div>@endif
                        @empty
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-images fs-1 d-block mb-2 opacity-50"></i>
                            No photos yet. Upload your first activity photo using the form on the left.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ============ CLIENTS TAB ============ --}}
    <div class="tab-pane fade" id="clients-panel" role="tabpanel">
        <div class="row g-4">

            {{-- Add client --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white border-0 pt-3 px-3">
                        <h6 class="fw-bold mb-0"><i class="bi bi-building-fill-add me-2"></i>Add Client</h6>
                        <small class="text-muted">Add a client logo to the public Clients page.</small>
                    </div>
                    <form action="{{ route('admin.cms.clients.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body p-3">
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Logo <span class="text-danger">*</span></label>
                                <input type="file" name="logo" class="form-control form-control-sm @error('logo') is-invalid @enderror"
                                       accept="image/*" required>
                                <div class="form-text">PNG (transparent), JPG, WEBP or SVG · max 2 MB</div>
                                @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Client Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                       class="form-control form-control-sm @error('name') is-invalid @enderror" placeholder="e.g. NMB Bank" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Website (optional)</label>
                                <input type="url" name="website" value="{{ old('website') }}"
                                       class="form-control form-control-sm" placeholder="https://...">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Order</label>
                                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="form-control form-control-sm">
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="cl_active" checked>
                                <label class="form-check-label small" for="cl_active">Show on site</label>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm w-100 fw-semibold">
                                <i class="bi bi-plus-lg me-1"></i>Add Client
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Existing clients --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white border-0 pt-3 px-3 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0"><i class="bi bi-buildings me-2"></i>Our Clients</h6>
                        <a href="{{ route('clients') }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-eye-fill me-1"></i>View public page
                        </a>
                    </div>
                    <div class="card-body p-3">
                        @forelse($clients as $client)
                        @if($loop->first)<div class="row g-3">@endif
                            <div class="col-md-3 col-6">
                                <div class="border rounded-3 p-2 h-100 d-flex flex-column">
                                    <div class="d-flex align-items-center justify-content-center" style="height:80px;background:#fff">
                                        <img src="{{ asset('storage/' . $client->logo_path) }}" alt="{{ $client->name }}"
                                             style="max-width:100%;max-height:70px;object-fit:contain">
                                    </div>
                                    <div class="small fw-semibold text-truncate mt-1" title="{{ $client->name }}">{{ $client->name }}</div>
                                    <div class="mt-1 d-flex align-items-center gap-1">
                                        @if($client->is_active)
                                        <span class="badge bg-success" style="font-size:.62rem">On</span>
                                        @else
                                        <span class="badge bg-secondary" style="font-size:.62rem">Off</span>
                                        @endif
                                        <div class="ms-auto d-flex gap-1">
                                            <button type="button" class="btn btn-sm btn-outline-primary py-0 px-1 btn-edit-client" title="Edit"
                                                    data-url="{{ route('admin.cms.clients.update', $client) }}"
                                                    data-name="{{ $client->name }}"
                                                    data-website="{{ $client->website }}"
                                                    data-sort="{{ $client->sort_order }}"
                                                    data-active="{{ $client->is_active ? '1' : '0' }}"
                                                    data-bs-toggle="modal" data-bs-target="#editClientModal">
                                                <i class="bi bi-pencil-fill" style="font-size:.72rem"></i>
                                            </button>
                                            <form action="{{ route('admin.cms.clients.destroy', $client) }}" method="POST"
                                                  onsubmit="return confirm('Remove this client?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger py-0 px-1" title="Delete">
                                                    <i class="bi bi-trash-fill" style="font-size:.72rem"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @if($loop->last)</div>@endif
                        @empty
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-buildings fs-1 d-block mb-2 opacity-50"></i>
                            No clients yet. Add your first client logo using the form on the left.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>{{-- /.tab-content --}}

{{-- ============ EDIT SERVICE MODAL ============ --}}
<div class="modal fade" id="editServiceModal" tabindex="-1" aria-labelledby="editServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editServiceForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="editServiceModalLabel">Edit Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="edit_title" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">Slug <span class="text-danger">*</span></label>
                            <input type="text" name="slug" id="edit_slug" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">Category <span class="text-danger">*</span></label>
                            <select name="category" id="edit_category" class="form-select form-select-sm" required>
                                <option value="security">Security &amp; Risk Management</option>
                                <option value="hr">Human Capital Solutions</option>
                                <option value="insurance">Insurance Advisory &amp; Brokerage</option>
                                <option value="cleaning">Facility Management Services</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Description <span class="text-danger">*</span></label>
                            <textarea name="description" id="edit_description" rows="3" class="form-control form-control-sm" required></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Icon</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><i class="bi bi-shield-check" id="editIconPreview"></i></span>
                                <select name="icon" id="edit_icon" class="form-select">
                                    @foreach(\App\Models\Service::ICON_OPTIONS as $ic => $lbl)
                                    <option value="{{ $ic }}">{{ $lbl }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Sort Order</label>
                            <input type="number" name="sort_order" id="edit_sort_order" class="form-control form-control-sm" min="0">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-check mb-0">
                                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="edit_is_active">
                                <label class="form-check-label fw-semibold small" for="edit_is_active">Active</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-semibold px-4">
                        <i class="bi bi-save-fill me-1"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ============ EDIT TEAM MEMBER MODAL ============ --}}
<div class="modal fade" id="editTeamModal" tabindex="-1" aria-labelledby="editTeamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editTeamForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="editTeamModalLabel">Edit Team Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="et_name" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Role / Title <span class="text-danger">*</span></label>
                            <input type="text" name="role" id="et_role" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Department</label>
                            <input type="text" name="department" id="et_department" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Email</label>
                            <input type="email" name="email" id="et_email" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">LinkedIn URL</label>
                            <input type="url" name="linkedin" id="et_linkedin" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Replace Photo</label>
                            <input type="file" name="photo" class="form-control form-control-sm" accept="image/jpeg,image/png,image/webp">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Bio</label>
                            <textarea name="bio" id="et_bio" rows="3" class="form-control form-control-sm"></textarea>
                        </div>
                        <div class="col-12" id="et_ceoMessageWrap" style="display:none">
                            <label class="form-label fw-semibold small">CEO Message</label>
                            <textarea name="ceo_message" id="et_ceo_message" rows="5" class="form-control form-control-sm"></textarea>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">Sort Order</label>
                            <input type="number" name="sort_order" id="et_sort" class="form-control form-control-sm" min="0">
                        </div>
                        <div class="col-md-3 d-flex align-items-end gap-3">
                            <div class="form-check mb-0">
                                <input type="checkbox" name="is_ceo" value="1" class="form-check-input" id="et_is_ceo">
                                <label class="form-check-label fw-semibold small" for="et_is_ceo">Is CEO</label>
                            </div>
                            <div class="form-check mb-0">
                                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="et_is_active">
                                <label class="form-check-label fw-semibold small" for="et_is_active">Active</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-semibold px-4">
                        <i class="bi bi-save-fill me-1"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ============ EDIT TESTIMONIAL MODAL ============ --}}
<div class="modal fade" id="editTestimonialModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editTestimonialForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Edit Testimonial</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Client Name <span class="text-danger">*</span></label>
                            <input type="text" name="client_name" id="ed_t_client" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Company</label>
                            <input type="text" name="company" id="ed_t_company" class="form-control form-control-sm">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Testimonial Quote <span class="text-danger">*</span></label>
                            <textarea name="quote" id="ed_t_quote" rows="4" class="form-control form-control-sm" required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Sort Order</label>
                            <input type="number" name="sort_order" id="ed_t_sort" class="form-control form-control-sm" min="0">
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check mb-1">
                                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="ed_t_active">
                                <label class="form-check-label fw-semibold small" for="ed_t_active">Active (show on homepage)</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-semibold px-4"><i class="bi bi-save-fill me-1"></i>Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ============ EDIT GALLERY PHOTO MODAL ============ --}}
<div class="modal fade" id="editGalleryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editGalleryForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Edit Photo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Replace Photo (optional)</label>
                        <input type="file" name="image" class="form-control form-control-sm" accept="image/*">
                        <div class="form-text">Leave empty to keep the current photo.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Title / Caption</label>
                        <input type="text" name="title" id="eg_title" class="form-control form-control-sm">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Description</label>
                        <textarea name="caption" id="eg_caption" rows="2" class="form-control form-control-sm"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Category</label>
                        <select name="category" id="eg_category" class="form-select form-select-sm">
                            <option value="">— General —</option>
                            @foreach(\App\Models\GalleryPhoto::CATEGORIES as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-7">
                            <label class="form-label fw-semibold small">Date</label>
                            <input type="date" name="event_date" id="eg_date" class="form-control form-control-sm">
                        </div>
                        <div class="col-5">
                            <label class="form-label fw-semibold small">Order</label>
                            <input type="number" name="sort_order" id="eg_sort" min="0" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_published" value="1" class="form-check-input" id="eg_pub">
                        <label class="form-check-label small" for="eg_pub">Published</label>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-semibold px-4"><i class="bi bi-save-fill me-1"></i>Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ============ EDIT CLIENT MODAL ============ --}}
<div class="modal fade" id="editClientModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editClientForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Edit Client</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Replace Logo (optional)</label>
                        <input type="file" name="logo" class="form-control form-control-sm" accept="image/*">
                        <div class="form-text">Leave empty to keep the current logo.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Client Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="ecl_name" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Website (optional)</label>
                        <input type="url" name="website" id="ecl_website" class="form-control form-control-sm" placeholder="https://...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Order</label>
                        <input type="number" name="sort_order" id="ecl_sort" min="0" class="form-control form-control-sm">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="ecl_active">
                        <label class="form-check-label small" for="ecl_active">Show on site</label>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm fw-semibold px-4"><i class="bi bi-save-fill me-1"></i>Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // Edit Client modal
    document.querySelectorAll('.btn-edit-client').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var form = document.getElementById('editClientForm');
            form.action = this.dataset.url;
            document.getElementById('ecl_name').value    = this.dataset.name || '';
            document.getElementById('ecl_website').value = this.dataset.website || '';
            document.getElementById('ecl_sort').value    = this.dataset.sort || 0;
            document.getElementById('ecl_active').checked = this.dataset.active === '1';
        });
    });

    // ── Auto-slug: build the slug from the title until the admin edits it ──
    var svcTitle = document.getElementById('svcTitle');
    var svcSlug  = document.getElementById('svcSlug');
    function slugify(s){ return s.toString().toLowerCase().trim()
        .replace(/[^a-z0-9\s-]/g,'').replace(/[\s_]+/g,'-').replace(/-+/g,'-').replace(/^-|-$/g,''); }
    if (svcTitle && svcSlug) {
        var slugTouched = false;
        svcSlug.addEventListener('input', function(){ slugTouched = svcSlug.value.trim() !== ''; });
        svcTitle.addEventListener('input', function(){ if(!slugTouched){ svcSlug.value = slugify(svcTitle.value); } });
    }

    // ── Icon preview (Add form) ──
    var addIconSelect = document.getElementById('addIconSelect');
    var addIconPreview = document.getElementById('addIconPreview');
    if (addIconSelect && addIconPreview) {
        addIconSelect.addEventListener('change', function(){ addIconPreview.className = 'bi bi-' + this.value; });
    }
    // ── Icon preview (Edit modal) ──
    var editIconSelect = document.getElementById('edit_icon');
    var editIconPreview = document.getElementById('editIconPreview');
    if (editIconSelect && editIconPreview) {
        editIconSelect.addEventListener('change', function(){ editIconPreview.className = 'bi bi-' + this.value; });
    }

    // Edit Testimonial modal
    document.querySelectorAll('.btn-edit-testimonial').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var form = document.getElementById('editTestimonialForm');
            form.action = this.dataset.url;
            document.getElementById('ed_t_client').value   = this.dataset.client || '';
            document.getElementById('ed_t_company').value  = this.dataset.company || '';
            document.getElementById('ed_t_quote').value    = this.dataset.quote || '';
            document.getElementById('ed_t_sort').value     = this.dataset.sort || 0;
            document.getElementById('ed_t_active').checked = this.dataset.active === '1';
        });
    });

    // Edit Gallery Photo modal
    document.querySelectorAll('.btn-edit-gallery').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var form = document.getElementById('editGalleryForm');
            form.action = this.dataset.url;
            document.getElementById('eg_title').value    = this.dataset.title || '';
            document.getElementById('eg_caption').value  = this.dataset.caption || '';
            document.getElementById('eg_category').value = this.dataset.category || '';
            document.getElementById('eg_date').value     = this.dataset.date || '';
            document.getElementById('eg_sort').value     = this.dataset.sort || 0;
            document.getElementById('eg_pub').checked    = this.dataset.published === '1';
        });
    });

    // Edit Service modal
    document.querySelectorAll('.btn-edit-service').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var form = document.getElementById('editServiceForm');
            form.action = this.dataset.url;
            document.getElementById('edit_title').value        = this.dataset.title;
            document.getElementById('edit_slug').value         = this.dataset.slug;
            document.getElementById('edit_category').value     = this.dataset.category;
            document.getElementById('edit_description').value  = this.dataset.description;
            document.getElementById('edit_icon').value         = this.dataset.icon || 'shield-check';
            var eip = document.getElementById('editIconPreview');
            if (eip) eip.className = 'bi bi-' + (document.getElementById('edit_icon').value || 'shield-check');
            document.getElementById('edit_sort_order').value   = this.dataset.sort;
            document.getElementById('edit_is_active').checked  = this.dataset.active === '1';
        });
    });

    // Edit Team Member modal
    document.querySelectorAll('.btn-edit-team').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var form = document.getElementById('editTeamForm');
            form.action = this.dataset.url;
            document.getElementById('et_name').value        = this.dataset.name;
            document.getElementById('et_role').value        = this.dataset.role;
            document.getElementById('et_department').value  = this.dataset.department || '';
            document.getElementById('et_email').value       = this.dataset.email || '';
            document.getElementById('et_linkedin').value    = this.dataset.linkedin || '';
            document.getElementById('et_bio').value         = this.dataset.bio || '';
            document.getElementById('et_ceo_message').value = this.dataset.ceoMessage || '';
            document.getElementById('et_sort').value        = this.dataset.sort;
            document.getElementById('et_is_ceo').checked    = this.dataset.isCeo === '1';
            document.getElementById('et_is_active').checked = this.dataset.active === '1';
            document.getElementById('et_ceoMessageWrap').style.display = this.dataset.isCeo === '1' ? 'block' : 'none';
        });
    });

    // Toggle CEO message field in Add form
    var isCeoChk = document.getElementById('isCeo');
    if (isCeoChk) {
        isCeoChk.addEventListener('change', function() {
            document.getElementById('ceoMessageWrap').style.display = this.checked ? 'block' : 'none';
        });
    }

    // Toggle CEO message field in Edit modal
    var etIsCeoChk = document.getElementById('et_is_ceo');
    if (etIsCeoChk) {
        etIsCeoChk.addEventListener('change', function() {
            document.getElementById('et_ceoMessageWrap').style.display = this.checked ? 'block' : 'none';
        });
    }
});
</script>

@endsection
