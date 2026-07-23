# Missing Icons — items requiring manual download

**Date:** 17 July 2026

## Why these are "missing"

The audit identified **128 unique icons** to replace (see `icon-audit.md`). The chosen flat-color SVG assets **could not be auto-downloaded in this environment**.

**Reason:** the assistant's environment cannot fetch or download binary/asset files from external sites (Icons8, Flaticon, Freepik, Vecteezy, or any other host). This is an environment restriction on scraping/downloading, not a licensing or payment problem. No CAPTCHA, login, or subscription was reached — the request cannot be initiated at all.

Per the task's own fallback rule ("If a download requires … skip it and record it"), every target asset is therefore listed here for **manual download by a human**, together with the exact recommended source.

## Fastest path to obtain all assets (recommended)

The full recommended pack is MIT-licensed and free with **no attribution**:

1. Go to `github.com/icons8/flat-color-icons`.
2. Download the repository (Code → Download ZIP), or clone it.
3. The `svg/` folder contains ~300 flat-color SVGs with transparent backgrounds.
4. Copy the needed files into `assets/icons/<category>/` using the **Replacement name** column from `icon-audit.md`.
5. Tell me when they're in place and I will rewire every `<i class="bi bi-…">` reference to the new SVGs across all 31 files, preserving sizing, spacing, colour and accessibility.

This single download covers the large majority of the 128 icons.

## Icons with no direct match in the flat-color pack (need a substitute)

A few brand/industry-specific glyphs used on the site have no exact equivalent in the flat-color pack and will need a hand-picked substitute or a custom SVG:

| Purpose | Current `bi-` | Note |
|---|---|---|
| WhatsApp | whatsapp | Use official WhatsApp brand SVG (brand guidelines apply) |
| X / Twitter | twitter-x | Use official X brand SVG |
| Personal accident | person-arms-up | No flat-color equivalent; closest is generic `person` |
| Traffic cone (security/construction) | cone-striped | Use `traffic-cone` or a construction substitute |
| Striped droplet (cleaning) | droplet-half | Use `water-drop` |
| WCF / shield-plus | shield-plus | Use generic `shield` (flat) |

## Status of deliverables

| Deliverable | Status |
|---|---|
| `icon-audit.md` | ✅ Complete |
| `icon-license.md` | ✅ Complete |
| `missing-icons.md` | ✅ Complete (this file) |
| `assets/icons/` scaffold | ✅ Created (13 category folders) |
| Downloaded SVG assets | ⛔ Blocked — manual download required (see above) |
| Code references updated | ⏳ Ready to run once assets are in place, **or** immediately via the filled-Bootstrap alternative below |

## Alternative I *can* execute right now (no downloads)

If you'd rather not download anything, I can modernise the look end-to-end today by switching the existing outline Bootstrap Icons to their **filled variants** (e.g. `bi-person` → `bi-person-fill`, `bi-envelope` → `bi-envelope-fill`). This:

- gives a modern, solid, filled-vector SaaS appearance,
- stays one consistent MIT-licensed family (no attribution, no new files),
- keeps your brand colours (`--kk-blue`, `--kk-gold`),
- touches only class names, so layout/spacing/accessibility are untouched.

It is monochrome-filled rather than multi-colour, but it is the only route that needs zero external downloads. Say the word and I'll apply it across all 31 files.
