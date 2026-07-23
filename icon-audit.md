# Icon Audit — Kikosi Kazi Company Ltd

**Date:** 17 July 2026
**Scope:** Full recursive scan of the Laravel application (`resources/`, `app/`, `routes/`, `public/`).

## Executive summary

| Metric | Value |
|---|---|
| Icon system in use | **Bootstrap Icons 1.11.3** (single family, loaded via CDN) |
| Total icon usages | **326** |
| Unique icons | **128** |
| Files containing icons | **31** |
| Other icon libraries found (Font Awesome, Heroicons, Lucide, Feather, Material, Remix, Phosphor) | **None** |
| Raster icons (PNG/JPG/WebP) | **None** (only `public/images/logo.png`) |
| Inline hand-drawn SVG icons | **None** |

**Key finding:** the project is **already standardized on one consistent icon family** (Bootstrap Icons, outline/monochrome style). There is no fragmented mix to consolidate. The requested work is therefore a **visual restyle** (outline → flat-color / filled vector), not a de-duplication clean-up.

Bootstrap Icons is loaded in six files:

- `resources/views/layouts/public.blade.php`
- `resources/views/layouts/admin.blade.php`
- `resources/views/layouts/candidate.blade.php`
- `resources/views/auth/login.blade.php`
- `resources/views/auth/register.blade.php`
- `resources/views/auth/verify-otp.blade.php`

## Icons by file (usage density)

| Usages | File |
|---|---|
| 25 | `candidate/profile/index.blade.php` |
| 21 | `candidate/documents/index.blade.php` |
| 20 | `public/about.blade.php` |
| 19 | `public/careers/show.blade.php` |
| 19 | `public/careers/index.blade.php` |
| 18 | `public/services/insurance.blade.php` |
| 18 | `admin/cms/index.blade.php` |
| 14 | `public/home.blade.php` |
| 14 | `layouts/candidate.blade.php` |
| 14 | `candidate/dashboard.blade.php` |
| 12 | `candidate/applications/create.blade.php` |
| 11 | `layouts/admin.blade.php` |
| 10 | `layouts/public.blade.php` |
| 9 | `public/services/index.blade.php` |
| 8 | `public/services/security.blade.php`, `hr.blade.php`, `cleaning.blade.php` |
| 7 | `industries`, `gallery`, `contact`, `applications/show`, `admin/dashboard` |
| 6 | `auth/verify-otp`, `auth/register`, `admin/jobs/index`, `admin/applicants/show` |
| ≤4 | `auth/login`, `admin/applicants/index`, `applications/index`, `admin/jobs/edit`, `admin/jobs/create` |

## Full inventory & recommended flat replacements

Categories below follow the requested taxonomy. The "Replacement" column gives the target flat-color icon name; the recommended source for every one is **Icons8 "Flat Color Icons"** (MIT-licensed, genuinely free — see `icon-license.md`) unless noted.

### Navigation
| Current (`bi-`) | Uses | Purpose | Replacement name |
|---|---|---|---|
| arrow-right | 10 | forward / next / CTA | `arrow-right.svg` |
| arrow-left | 6 | back | `arrow-left.svg` |
| list | 3 | mobile menu toggle | `menu.svg` |
| chevron-down | 1 | dropdown | `chevron-down.svg` |
| arrow-up-right-square | 1 | external link | `external-link.svg` |
| arrow-clockwise | 1 | refresh | `refresh.svg` |

### Dashboard
| Current | Uses | Purpose | Replacement |
|---|---|---|---|
| speedometer2 | 2 | dashboard home | `dashboard.svg` |
| briefcase | 16 | jobs / vacancies | `briefcase.svg` |
| briefcase-fill | 1 | active jobs | `briefcase.svg` |
| bullseye | 1 | goals / targets | `target.svg` |
| bank | 1 | finance / payments | `bank.svg` |
| calculator | 1 | salary / calc | `calculator.svg` |

### Forms
| Current | Uses | Purpose | Replacement |
|---|---|---|---|
| save | 8 | save / submit | `save.svg` |
| pencil | 4 | edit | `edit.svg` |
| pencil-square | 1 | edit record | `edit.svg` |
| trash | 7 | delete | `trash.svg` |
| search | 2 | search field | `search.svg` |
| funnel | 3 | filter | `filter.svg` |
| plus-lg | 6 | add | `add.svg` |
| plus-circle | 4 | add item | `add.svg` |
| check-lg | 2 | confirm | `check.svg` |
| check2-circle | 3 | completed | `checkmark.svg` |
| x-lg | 4 | close / clear | `close.svg` |
| eye | 6 | view / preview | `visible.svg` |
| cloud-upload | 2 | upload | `upload.svg` |
| download | 3 | download | `download.svg` |

### Actions
| Current | Uses | Purpose | Replacement |
|---|---|---|---|
| send | 5 | send / apply | `send.svg` |
| send-check | 1 | sent confirmation | `send.svg` |
| magic | 2 | auto-fill / AI parse | `magic-wand.svg` |
| tools | 1 | maintenance | `maintenance.svg` |
| hammer | 1 | construction/industry | `hammer.svg` |
| recycle | 1 | sustainability | `recycle.svg` |
| cart4 | 1 | procurement | `shopping-cart.svg` |

### Users
| Current | Uses | Purpose | Replacement |
|---|---|---|---|
| person-fill | 7 | user avatar | `user.svg` |
| person | 4 | user | `user.svg` |
| person-circle | 4 | profile | `user-circle.svg` |
| people | 8 | team / applicants | `group.svg` |
| people-fill | 2 | staff | `group.svg` |
| person-badge | 2 | role / staff id | `id-badge.svg` |
| person-lines-fill | 2 | contact details | `contact-card.svg` |
| person-plus | 1 | register / add user | `add-user.svg` |
| person-check | 1 | approved candidate | `approve-user.svg` |
| person-arms-up | 1 | personal accident | `person.svg` |
| mortarboard | 6 | education / discipline | `graduation-cap.svg` |
| mortarboard-fill | 2 | qualification | `graduation-cap.svg` |

### Settings
| Current | Uses | Purpose | Replacement |
|---|---|---|---|
| gear | 1 | settings | `settings.svg` |
| lock | 3 | password / secure | `lock.svg` |
| lock-fill | 1 | secured | `lock.svg` |
| shield-check | 5 | verified / trust | `shield.svg` |
| shield-lock | 1 | security service | `shield.svg` |
| shield-plus | 1 | WCF / protection | `shield.svg` |

### Authentication
| Current | Uses | Purpose | Replacement |
|---|---|---|---|
| box-arrow-in-right | 2 | login | `login.svg` |
| box-arrow-right | 2 | logout | `logout.svg` |
| envelope-check | 1 | OTP verify | `verify-email.svg` |
| patch-check-fill | 1 | verified badge | `verified.svg` |

### Analytics
| Current | Uses | Purpose | Replacement |
|---|---|---|---|
| graph-up-arrow | 2 | growth / results | `growth.svg` |
| star-fill | 3 | rating / featured | `star.svg` |
| star | 1 | rating | `star.svg` |
| stars | 2 | excellence | `stars.svg` |
| award | 1 | achievement | `award.svg` |
| award-fill | 1 | achievement | `award.svg` |
| hand-thumbs-up-fill | 1 | approval | `thumbs-up.svg` |

### Notifications & communication
| Current | Uses | Purpose | Replacement |
|---|---|---|---|
| envelope | 6 | email | `email.svg` |
| envelope-fill | 1 | email | `email.svg` |
| bell | 1 | alerts | `bell.svg` |
| chat-dots | 4 | messages / support | `chat.svg` |
| chat-quote | 1 | testimonial | `testimonial.svg` |
| quote | 2 | testimonial | `quote.svg` |
| telephone | 2 | phone | `phone.svg` |
| telephone-fill | 1 | phone | `phone.svg` |
| headset | 1 | support | `support.svg` |
| whatsapp | 1 | WhatsApp contact | `whatsapp.svg` |
| inbox | 2 | inbox / empty state | `inbox.svg` |

### Media & files
| Current | Uses | Purpose | Replacement |
|---|---|---|---|
| image | 6 | photo / gallery | `image.svg` |
| camera-video | 1 | video | `video.svg` |
| file-earmark-pdf | 6 | CV / PDF | `pdf.svg` |
| file-text | 1 | document | `document.svg` |
| file-earmark-text | 1 | document | `document.svg` |
| file-earmark-check | 1 | verified doc | `document-ok.svg` |
| folder2 | 2 | folder | `folder.svg` |
| folder2-open | 4 | open folder / files | `open-folder.svg` |
| calendar3 | 4 | date / deadline | `calendar.svg` |
| calendar-event | 1 | event date | `calendar.svg` |
| calendar-check | 1 | scheduled | `calendar-ok.svg` |
| clock | 1 | time / pending | `clock.svg` |
| clock-fill | 1 | time | `clock.svg` |

### Social & location
| Current | Uses | Purpose | Replacement |
|---|---|---|---|
| linkedin | 3 | LinkedIn | `linkedin.svg` |
| facebook | 1 | Facebook | `facebook.svg` |
| twitter-x | 1 | X / Twitter | `twitterx.svg` |
| globe | 4 | website / global | `globe.svg` |
| globe-africa | 1 | Tanzania / Africa | `globe.svg` |
| map | 1 | location map | `map.svg` |
| geo-alt | 7 | address pin | `location.svg` |
| geo-alt-fill | 2 | address pin | `location.svg` |

### Status
| Current | Uses | Purpose | Replacement |
|---|---|---|---|
| check-circle-fill | 19 | success / done | `ok.svg` |
| check-circle | 2 | success | `ok.svg` |
| check2-circle | (forms) | completed | `checkmark.svg` |
| clipboard-check | 1 | task done | `checklist.svg` |
| clipboard2-check | 1 | compliance | `checklist.svg` |
| clipboard2-pulse | 1 | risk assessment | `health-checkup.svg` |
| x-circle-fill | 2 | error / rejected | `cancel.svg` |
| exclamation-triangle-fill | 4 | warning | `warning.svg` |
| exclamation-triangle | 1 | warning | `warning.svg` |
| exclamation-circle-fill | 2 | alert | `error.svg` |
| exclamation-circle | 2 | alert | `error.svg` |
| info-circle | 7 | information | `info.svg` |
| slash-circle | 2 | not eligible / blocked | `no-entry.svg` |
| circle | 1 | neutral status dot | `circle.svg` |
| flag | 2 | flagged / priority | `flag.svg` |

### Miscellaneous (industry & brand)
| Current | Uses | Purpose | Replacement |
|---|---|---|---|
| umbrella-fill | 2 | insurance | `umbrella.svg` |
| shield-* | (settings) | protection | `shield.svg` |
| building | 3 | company / client | `building.svg` |
| building-fill | 1 | company | `building.svg` |
| car-front | 2 | motor insurance | `car.svg` |
| truck | 1 | fleet | `truck.svg` |
| airplane | 1 | travel insurance | `airplane.svg` |
| fire | 1 | fire insurance | `fire.svg` |
| droplet-half | 1 | cleaning / water | `water-drop.svg` |
| cone-striped | 1 | construction / security | `traffic-cone.svg` |
| safe2 | 1 | theft/burglary cover | `safe.svg` |
| scales | 1 | legal / compliance | `scales.svg` |
| heart-pulse | 2 | group life / medical | `heart-with-pulse.svg` |
| lightbulb | 1 | ideas / innovation | `idea.svg` |
| geo / map | (social) | location | `location.svg` |

> Note: `bi` appeared once as a false-positive token in the grep (part of a class fragment), not an actual icon — excluded from the 128 count where relevant.

## Recommended target: one flat-color family

To satisfy "single consistent flat-color / 2D flat vector family" with clean licensing, the recommended pack is **Icons8 Flat Color Icons** — an MIT-licensed open-source set of ~300 multi-color flat SVGs (see `icon-license.md`). Every icon above maps cleanly to a member of that pack, so the result reads as one premium SaaS system.

See `missing-icons.md` for why the SVG assets could not be auto-downloaded in this environment and exactly how to obtain them, and `icon-license.md` for the licence position.
