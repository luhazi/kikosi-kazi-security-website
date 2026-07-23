<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>CV — {{ $profile->user->name ?? 'Candidate' }}</title>
<style>
/* ── RESET & BASE ───────────────────────────────────────── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { font-size: 14px; }
body {
    font-family: 'Segoe UI', Arial, sans-serif;
    color: #1a1a1a;
    background: #f5f5f5;
    line-height: 1.5;
}

/* ── PRINT BUTTON (hidden when printing) ─────────────────── */
.print-bar {
    background: #0D47A1;
    color: #fff;
    padding: 12px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    position: sticky;
    top: 0;
    z-index: 100;
}
.print-bar h6 { margin: 0; font-size: 1rem; }
.btn-print {
    background: #FFB300;
    color: #000;
    border: none;
    padding: 8px 20px;
    font-weight: 700;
    font-size: .9rem;
    border-radius: 4px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
}
.btn-print:hover { background: #FFA000; }

/* ── PAGE WRAPPER ──────────────────────────────────────── */
.page-wrap {
    max-width: 860px;
    margin: 24px auto;
    background: #fff;
    box-shadow: 0 4px 24px rgba(0,0,0,.12);
    border-radius: 4px;
    overflow: hidden;
}

/* ── HEADER ────────────────────────────────────────────── */
.cv-header {
    background: linear-gradient(135deg, #0D47A1 0%, #1565C0 100%);
    color: #fff;
    padding: 40px 40px 32px;
    display: flex;
    gap: 28px;
    align-items: flex-start;
}
.cv-header .photo {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid rgba(255,255,255,.4);
    flex-shrink: 0;
}
.cv-header .photo-placeholder {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    background: rgba(255,255,255,.15);
    border: 4px solid rgba(255,255,255,.3);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 2.5rem;
    color: rgba(255,255,255,.6);
}
.cv-header .info h1 {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 4px;
}
.cv-header .info .tagline {
    color: rgba(255,255,255,.7);
    font-size: .95rem;
    margin-bottom: 12px;
}
.cv-header .meta-row {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    font-size: .82rem;
    color: rgba(255,255,255,.85);
}
.cv-header .meta-row span {
    display: flex;
    align-items: center;
    gap: 5px;
}

/* ── BODY LAYOUT ─────────────────────────────────────────── */
.cv-body {
    display: flex;
    gap: 0;
}
.cv-sidebar {
    width: 220px;
    flex-shrink: 0;
    background: #F0F4FF;
    padding: 28px 20px;
    border-right: 1px solid #E0E8FF;
}
.cv-main {
    flex: 1;
    padding: 28px 32px;
}

/* ── SECTION TITLES ──────────────────────────────────────── */
.section-title {
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: #0D47A1;
    margin-bottom: 12px;
    padding-bottom: 6px;
    border-bottom: 2px solid #0D47A1;
}

/* ── SIDEBAR SECTIONS ────────────────────────────────────── */
.sidebar-section { margin-bottom: 24px; }
.sidebar-section p, .sidebar-section li {
    font-size: .82rem;
    color: #444;
    margin-bottom: 6px;
}
.sidebar-section ul { list-style: none; padding: 0; }
.sidebar-section ul li::before { content: "▸ "; color: #0D47A1; }
.contact-line { display: flex; align-items: flex-start; gap: 6px; margin-bottom: 8px; font-size: .82rem; color: #444; }
.contact-label { font-weight: 600; color: #0D47A1; font-size: .75rem; text-transform: uppercase; letter-spacing: .05em; display: block; margin-bottom: 1px; }

/* ── MAIN SECTIONS ───────────────────────────────────────── */
.main-section { margin-bottom: 28px; }

/* Timeline entry */
.timeline-entry { margin-bottom: 18px; padding-left: 14px; border-left: 3px solid #0D47A1; }
.timeline-entry:last-child { margin-bottom: 0; }
.timeline-title { font-weight: 700; font-size: .92rem; color: #0D47A1; }
.timeline-sub { font-size: .82rem; color: #555; margin-bottom: 2px; }
.timeline-date { font-size: .75rem; color: #888; margin-bottom: 5px; }
.resp-list {
                    margin: .3rem 0 0 1rem;
                    padding: 0;
                    list-style: disc;
                    font-size: .83rem;
                    color: #444;
                    line-height: 1.55;
                }
                .resp-list li { margin-bottom: .25rem; }
                .timeline-body { font-size: .82rem; color: #444; white-space: pre-line; }

/* Bio */
.bio-text { font-size: .85rem; color: #444; line-height: 1.7; }

/* ── FOOTER ──────────────────────────────────────────────── */
.cv-footer {
    background: #F0F4FF;
    border-top: 1px solid #E0E8FF;
    text-align: center;
    padding: 12px;
    font-size: .75rem;
    color: #888;
}

/* ── PRINT STYLES ────────────────────────────────────────── */
@media print {
    body { background: #fff; font-size: 12px; }
    .print-bar { display: none !important; }
    .page-wrap { max-width: 100%; margin: 0; box-shadow: none; border-radius: 0; }
    .cv-header { padding: 24px 28px 20px; }
    .cv-sidebar { padding: 20px 16px; }
    .cv-main { padding: 20px 24px; }
}
</style>
</head>
<body>

{{-- Print bar (hidden on print) --}}
<div class="print-bar">
    <h6>📄 {{ $profile->user->name ?? 'Candidate' }} — Curriculum Vitae</h6>
    <div style="display:flex;gap:10px;align-items:center">
        <a href="{{ route('candidate.dashboard') }}" style="color:rgba(255,255,255,.7);font-size:.85rem;text-decoration:none">← Back to Dashboard</a>
        <button class="btn-print" onclick="window.print()">🖨 Print / Save as PDF</button>
    </div>
</div>

<div class="page-wrap">

    {{-- HEADER --}}
    <div class="cv-header">
        @if($profile->profile_photo)
        <img src="{{ asset('storage/' . $profile->profile_photo) }}" alt="Photo" class="photo">
        @else
        <div class="photo-placeholder">👤</div>
        @endif

        <div class="info">
            <h1>{{ $profile->user->name ?? 'Full Name' }}</h1>
            @if($profile->bio)
            <p class="tagline">{{ Str::limit($profile->bio, 120) }}</p>
            @endif
            <div class="meta-row">
                @if($profile->phone)
                <span>📞 {{ $profile->phone }}</span>
                @endif
                @if($profile->user->email)
                <span>✉ {{ $profile->user->email }}</span>
                @endif
                @if($profile->city && $profile->region)
                <span>📍 {{ $profile->city }}, {{ $profile->region }}</span>
                @endif
                @if($profile->nationality)
                <span>🌍 {{ $profile->nationality }}</span>
                @endif
            </div>
        </div>
    </div>

    <div class="cv-body">

        {{-- SIDEBAR --}}
        <aside class="cv-sidebar">

            {{-- Personal Details --}}
            <div class="sidebar-section">
                <div class="section-title">Personal Details</div>

                @if($profile->date_of_birth)
                <div>
                    <span class="contact-label">Date of Birth</span>
                    <span>{{ $profile->date_of_birth->format('d M Y') }}</span>
                </div>
                @endif

                @if($profile->gender)
                <div style="margin-top:8px">
                    <span class="contact-label">Gender</span>
                    <span>{{ ucfirst($profile->gender) }}</span>
                </div>
                @endif

                @if($profile->national_id)
                <div style="margin-top:8px">
                    <span class="contact-label">National ID</span>
                    <span>{{ $profile->national_id }}</span>
                </div>
                @endif

                @if($profile->address)
                <div style="margin-top:8px">
                    <span class="contact-label">Address</span>
                    <span>{{ $profile->address }}, {{ $profile->city }}, {{ $profile->region }}</span>
                </div>
                @endif

                @if($profile->alternate_phone)
                <div style="margin-top:8px">
                    <span class="contact-label">Alt. Phone</span>
                    <span>{{ $profile->alternate_phone }}</span>
                </div>
                @endif
            </div>

            {{-- Documents --}}
            @if($profile->documents && $profile->documents->count())
            <div class="sidebar-section">
                <div class="section-title">Documents</div>
                <ul>
                    @foreach($profile->documents as $doc)
                    <li>{{ $doc->name ?? $doc->document_type }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Declaration --}}
            <div class="sidebar-section" style="margin-top:auto">
                <div class="section-title">Declaration</div>
                <p style="font-size:.78rem;color:#666;line-height:1.6">
                    I hereby declare that the information provided in this CV is accurate and true to the best of my knowledge.
                </p>
                <div style="margin-top:24px">
                    <div style="border-bottom:1px solid #aaa;width:120px;margin-bottom:4px"></div>
                    <span style="font-size:.75rem;color:#888">Signature</span>
                </div>
                <div style="margin-top:12px">
                    <div style="border-bottom:1px solid #aaa;width:120px;margin-bottom:4px"></div>
                    <span style="font-size:.75rem;color:#888">Date</span>
                </div>
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        <main class="cv-main">

            {{-- Bio / Profile Summary --}}
            @if($profile->bio)
            <div class="main-section">
                <div class="section-title">Profile Summary</div>
                <p class="bio-text">{{ $profile->bio }}</p>
            </div>
            @endif

            {{-- Education --}}
            @if($profile->education && $profile->education->count())
            <div class="main-section">
                <div class="section-title">Education</div>
                @foreach($profile->education->sortByDesc('year_completed') as $edu)
                <div class="timeline-entry">
                    <div class="timeline-title">{{ $edu->qualification }}</div>
                    <div class="timeline-sub">{{ $edu->institution }} — {{ $edu->field_of_study }}</div>
                    <div class="timeline-date">
                        Graduated {{ $edu->year_completed }}
                        @if($edu->grade) · Grade: {{ $edu->grade }} @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Work Experience --}}
            @if($profile->experience && $profile->experience->count())
            <div class="main-section">
                <div class="section-title">Work Experience</div>
                @foreach($profile->experience->sortByDesc('start_date') as $exp)
                <div class="timeline-entry">
                    <div class="timeline-title">{{ $exp->job_title }}</div>
                    <div class="timeline-sub">{{ $exp->employer }}</div>
                    <div class="timeline-date">
                        {{ \Carbon\Carbon::parse($exp->start_date)->format('M Y') }} —
                        {{ $exp->end_date ? \Carbon\Carbon::parse($exp->end_date)->format('M Y') : 'Present' }}
                    </div>
                    @if($exp->responsibilities)
                    <div class="timeline-body">
                        @php
                            $respCv = preg_replace(
                                '/\\b(?:CERTIFICATIONS?(?:\\s*[&]\\s*PROFESSIONAL\\s+REGISTRATION)?|REFEREES?|REFERENCES?|TECHNICAL\\s+SKILLS?)\\b.*/si',
                                '', $exp->responsibilities
                            );
                            $respCv = trim($respCv);
                            if (str_contains($respCv, "\n")) {
                                $cvLines = array_filter(array_map('trim', explode("\n", $respCv)));
                            } else {
                                $cvLines = preg_split('/(?<=[.!?])\\s+(?=[A-Z\\d])/', $respCv) ?: [$respCv];
                                $cvLines = array_filter(array_map('trim', $cvLines));
                            }
                        @endphp
                        <ul class="resp-list">
                            @foreach($cvLines as $pt)
                            <li>{{ ltrim(trim($pt), '•· ') }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @endif

            {{-- Referees placeholder --}}
            <div class="main-section">
                <div class="section-title">Referees</div>
                <p style="font-size:.82rem;color:#888;font-style:italic">Available upon request.</p>
            </div>
        </main>

    </div>{{-- /.cv-body --}}

    <div class="cv-footer">
        Generated by Kikosi Kazi Platform · {{ now()->format('d M Y') }}
        @if($profile->user->email) · {{ $profile->user->email }} @endif
    </div>

</div>{{-- /.page-wrap --}}

</body>
</html>
