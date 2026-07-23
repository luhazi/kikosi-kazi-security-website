@extends('layouts.public')
@section('title', 'Photo Gallery — Kikosi Kazi Security')
@section('meta_description', 'See Kikosi Kazi Security in action — our people, operations and day-to-day work delivering integrated security and business support services across Tanzania.')
@section('og_title', 'Photo Gallery | Kikosi Kazi Security')
@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ request()->root() }}" },
        { "@@type": "ListItem", "position": 2, "name": "Gallery", "item": "{{ request()->url() }}" }
    ]
}
</script>
@endpush
@section('navbar-class', 'solid')

@section('content')

{{-- HERO --}}
<section class="py-5" style="background:linear-gradient(135deg,#0D47A1 0%,#1565C0 100%);min-height:220px;display:flex;align-items:center">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active text-white">Photo Gallery</li>
            </ol>
        </nav>
        <h1 class="display-5 fw-bold text-white mb-2">Photo Gallery</h1>
        <p class="lead text-white-50 mb-0">Our people and our work, day by day — across Tanzania.</p>
    </div>
</section>

{{-- GALLERY --}}
<section class="section-py bg-white">
    <div class="container">

        <div class="text-center mb-4">
            <h2 class="section-title">Our <span>Activities</span></h2>
            <div class="gold-bar mx-auto"></div>
            <p class="text-muted mt-3 mb-0">
                {{ $totalPhotos }} {{ \Illuminate\Support\Str::plural('photo', $totalPhotos) }} from our operations, training, events and community work.
            </p>
        </div>

        {{-- Category filter --}}
        @if($totalPhotos)
        <div class="d-flex flex-wrap justify-content-center gap-2 mb-5">
            <a href="{{ route('gallery') }}"
               class="btn btn-sm {{ !$activeCategory ? 'btn-kk' : 'btn-outline-secondary' }} px-3">
                All <span class="badge bg-light text-dark ms-1">{{ $totalPhotos }}</span>
            </a>
            @foreach(\App\Models\GalleryPhoto::CATEGORIES as $key => $label)
                @if(($counts[$key] ?? 0) > 0)
                <a href="{{ route('gallery', ['category' => $key]) }}"
                   class="btn btn-sm {{ $activeCategory === $key ? 'btn-kk' : 'btn-outline-secondary' }} px-3">
                    {{ $label }} <span class="badge bg-light text-dark ms-1">{{ $counts[$key] }}</span>
                </a>
                @endif
            @endforeach
        </div>
        @endif

        {{-- Photo grid — masonry so portrait & landscape photos show in full (no cropping) --}}
        @if($photos->count())
        <div class="kk-masonry">
            @foreach($photos as $photo)
            <div class="kk-photo rounded-3 overflow-hidden position-relative"
                 role="button" tabindex="0"
                 data-bs-toggle="modal" data-bs-target="#photoModal"
                 data-src="{{ asset('storage/' . $photo->image_path) }}"
                 data-title="{{ $photo->title }}"
                 data-caption="{{ $photo->caption }}"
                 data-meta="{{ $photo->categoryLabel() }}{{ $photo->event_date ? ' · '.$photo->event_date->format('d M Y') : '' }}">
                <img src="{{ asset('storage/' . $photo->image_path) }}"
                     alt="{{ $photo->title ?: 'Kikosi Kazi activity photo' }}" loading="lazy">
                <div class="kk-photo-overlay">
                    <div class="text-white">
                        @if($photo->title)
                        <div class="fw-semibold">{{ $photo->title }}</div>
                        @endif
                        <small class="text-white-50">
                            {{ $photo->categoryLabel() }}@if($photo->event_date) · {{ $photo->event_date->format('d M Y') }}@endif
                        </small>
                    </div>
                    <i class="bi bi-zoom-in text-white fs-5"></i>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                    <i class="bi bi-images display-4 text-muted mb-3"></i>
                    <h5 class="fw-bold text-muted mb-2">No photos yet</h5>
                    <p class="text-muted mb-0">
                        @if($activeCategory)
                            There are no photos in this category yet.
                            <a href="{{ route('gallery') }}" class="fw-semibold text-decoration-none">View all photos</a>.
                        @else
                            Our gallery is being updated. Please check back soon.
                        @endif
                    </p>
                </div>
            </div>
        </div>
        @endif

        @if($photos->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $photos->links() }}
        </div>
        @endif

    </div>
</section>

{{-- Lightbox --}}
<div class="modal fade" id="photoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 overflow-hidden">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold" id="pmTitle" style="color:var(--kk-blue)"></h5>
                    <small class="text-muted" id="pmMeta"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-3">
                <img id="pmImage" src="" alt="" class="w-100 rounded-3">
                <p class="text-muted mt-3 mb-0" id="pmCaption" style="line-height:1.7"></p>
            </div>
        </div>
    </div>
</div>

<style>
/* Masonry columns: full portrait & landscape photos, no cropping */
.kk-masonry { column-count: 3; column-gap: 1rem; }
@media (max-width: 991.98px) { .kk-masonry { column-count: 2; } }
@media (max-width: 575.98px) { .kk-masonry { column-count: 1; } }
.kk-photo {
    break-inside: avoid; -webkit-column-break-inside: avoid;
    margin-bottom: 1rem; cursor: pointer; background: #f1f3f7; display: block;
}
.kk-photo img { width:100%; height:auto; display:block; transition: transform .5s ease; }
.kk-photo:hover img { transform: scale(1.04); }
.kk-photo-overlay {
    position:absolute; inset:0;
    background:linear-gradient(to top, rgba(15,30,67,.85) 0%, rgba(15,30,67,.15) 45%, rgba(15,30,67,0) 100%);
    display:flex; align-items:flex-end; justify-content:space-between; gap:.5rem;
    padding:14px; opacity:0; transition:opacity .3s ease;
}
.kk-photo:hover .kk-photo-overlay, .kk-photo:focus .kk-photo-overlay { opacity:1; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('photoModal');
    if (!modal) return;
    modal.addEventListener('show.bs.modal', function (ev) {
        var el = ev.relatedTarget; if (!el) return;
        document.getElementById('pmImage').src           = el.dataset.src || '';
        document.getElementById('pmTitle').textContent   = el.dataset.title || 'Photo';
        document.getElementById('pmMeta').textContent    = el.dataset.meta || '';
        document.getElementById('pmCaption').textContent = el.dataset.caption || '';
    });
    // allow keyboard activation of the photo tiles
    document.querySelectorAll('.kk-photo').forEach(function (tile) {
        tile.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); tile.click(); }
        });
    });
});
</script>

@endsection
