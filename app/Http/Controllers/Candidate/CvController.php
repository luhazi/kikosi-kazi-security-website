<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CvController extends Controller
{
    public function show(): View
    {
        $profile = Auth::user()
            ->candidateProfile()
            ->with(['education', 'experience', 'documents'])
            ->firstOrFail();

        return view('candidate.cv', compact('profile'));
    }
}
