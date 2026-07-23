KIKOSI KAZI — HERO BACKGROUND SLIDESHOW
=======================================

The homepage hero plays an automatic slideshow (video + image mix) behind the
headline. Drop your files in THIS folder using these exact names:

  slide-1.mp4   <- short background video clip (muted, autoplay, loops)
  slide-1.jpg   <- poster image shown while slide-1.mp4 loads (optional but recommended)
  slide-2.jpg   <- still image slide (animated zoom)
  slide-3.jpg   <- still image slide (animated zoom)

To add MORE slides, or change types/names, edit the $heroSlides list near the
top of:  resources/views/public/home.blade.php

RECOMMENDED SPECS
-----------------
Video : MP4 (H.264), 1920x1080, no audio needed, 6-15 sec, KEEP IT SMALL
        (aim for under ~5 MB — big files slow the page). Landscape.
Images: JPG/WEBP, 1920x1080 or larger, landscape, high quality.

TIPS
----
- Content is on the LEFT, so choose footage/photos whose subject sits toward
  the RIGHT so the headline stays clear.
- The dark overlay is applied automatically for text legibility.
- Slides auto-advance every 6.5 seconds and loop forever.
- Missing files? No problem — the hero simply falls back to the blue gradient.
