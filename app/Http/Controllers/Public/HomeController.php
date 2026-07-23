<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $jobs = Job::active()->latest('deadline')->take(6)->get();
        // CMS-managed services grouped by category (drives the "What's Included" lists)
        $cmsServices = Service::active()->orderBy('sort_order')->get()->groupBy('category');
        $testimonials = Testimonial::active()->orderBy('sort_order')->get();

        return view('public.home', compact('jobs', 'cmsServices', 'testimonials'));
    }
}
