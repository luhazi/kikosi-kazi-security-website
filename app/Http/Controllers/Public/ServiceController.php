<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::active()
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category');

        return view('public.services.index', compact('services'));
    }

    public function show(string $category): View
    {
        $allowed = ['security', 'hr', 'insurance', 'cleaning'];

        if (! in_array($category, $allowed)) {
            abort(404);
        }

        $services = Service::active()
            ->where('category', $category)
            ->orderBy('sort_order')
            ->get();

        return view("public.services.{$category}", compact('services', 'category'));
    }
}
