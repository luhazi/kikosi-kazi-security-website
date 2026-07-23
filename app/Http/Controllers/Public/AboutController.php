<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(?string $section = null): View
    {
        $ceo         = TeamMember::where('is_ceo', true)->where('is_active', true)->first();
        $teamMembers = TeamMember::where('is_active', true)->orderBy('sort_order')->get();

        return view('public.about', compact('ceo', 'teamMembers', 'section'));
    }
}
