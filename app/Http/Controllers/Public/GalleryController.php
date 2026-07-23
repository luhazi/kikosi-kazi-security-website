<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\GalleryPhoto;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(Request $request): View
    {
        $query = GalleryPhoto::published()
            ->orderBy('sort_order')
            ->orderByDesc('event_date')
            ->orderByDesc('created_at');

        $activeCategory = $request->get('category');

        if ($activeCategory && array_key_exists($activeCategory, GalleryPhoto::CATEGORIES)) {
            $query->where('category', $activeCategory);
        } else {
            $activeCategory = null;
        }

        // Photo count per category (for the filter pills)
        $counts = GalleryPhoto::published()
            ->selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        return view('public.gallery', [
            'photos'         => $query->paginate(24)->withQueryString(),
            'activeCategory' => $activeCategory,
            'counts'         => $counts,
            'totalPhotos'    => GalleryPhoto::published()->count(),
        ]);
    }
}
