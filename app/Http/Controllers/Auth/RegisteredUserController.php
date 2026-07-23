<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle registration: create unverified user, send OTP, redirect to verify page.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms'    => ['accepted'],
        ], [
            'terms.accepted' => 'You must agree to the Terms & Conditions and confirm your details are correct and true.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            // email_verified_at is intentionally left null
        ]);

        $user->assignRole('candidate');

        // Generate and send OTP
        OtpVerificationController::sendOtp($user);

        // Store user id in session for the verify page
        $request->session()->put('pending_verification_user_id', $user->id);

        return redirect()->route('otp.show')
            ->with('status', 'Account created! Please check your email for the verification code.');
    }
}
