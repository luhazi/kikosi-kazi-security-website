<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(Request $request): View
    {
        return view('auth.login', ['admin' => $request->routeIs('admin.login')]);
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = Auth::user();

        // ── Enforce the correct login portal for this account ──────────────
        // Staff (admin/HR/recruiter/content) must use /admin/login; candidates must use /login.
        $portal  = $request->input('portal', 'candidate');
        $isStaff = $user->hasAnyRole(['super_admin', 'hr_manager', 'recruitment_officer', 'content_editor']);

        if ($portal === 'admin' && ! $isStaff) {
            // A job applicant tried to use the staff login
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->withErrors([
                'email' => 'This is the staff login. Please sign in with your applicant account on the candidate login page.',
            ]);
        }

        if ($portal !== 'admin' && $isStaff) {
            // A staff member tried to use the candidate login
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('admin.login')->withErrors([
                'email' => 'Staff accounts must sign in via the Admin Panel login.',
            ]);
        }

        // Block unverified accounts — send fresh OTP and redirect
        if (is_null($user->email_verified_at)) {
            Auth::logout();
            $request->session()->put('pending_verification_user_id', $user->id);
            OtpVerificationController::sendOtp($user);

            return redirect()->route('otp.show')
                ->withErrors(['email' => 'Please verify your email first. A new code has been sent to ' . $user->email]);
        }

        $request->session()->regenerate();

        if ($user->hasAnyRole(['super_admin', 'hr_manager', 'recruitment_officer', 'content_editor'])) {
            return redirect(route('admin.dashboard'));
        }

        if ($user->hasRole('candidate')) {
            return redirect(route('candidate.dashboard'));
        }

        return redirect('/');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
