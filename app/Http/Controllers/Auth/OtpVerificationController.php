<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpVerificationMail;
use App\Models\OtpVerification;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class OtpVerificationController extends Controller
{
    /** Show the OTP entry form */
    public function show(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('pending_verification_user_id')) {
            return redirect()->route('register');
        }

        return view('auth.verify-otp');
    }

    /** Verify the submitted OTP code */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $userId = $request->session()->get('pending_verification_user_id');
        if (! $userId) {
            return redirect()->route('register')
                ->withErrors(['code' => 'Session expired. Please register again.']);
        }

        $user = User::find($userId);
        if (! $user) {
            return redirect()->route('register');
        }

        /** @var OtpVerification|null $otp */
        $otp = OtpVerification::where('user_id', $userId)
            ->where('code', $request->code)
            ->latest()
            ->first();

        if (! $otp) {
            return back()->withErrors(['code' => 'Invalid verification code. Please try again.']);
        }

        if ($otp->isExpired()) {
            $otp->delete();
            return back()->withErrors(['code' => 'This code has expired. Please request a new one.']);
        }

        $user->email_verified_at = Carbon::now();
        $user->save();

        $otp->delete();
        OtpVerification::where('user_id', $userId)->delete();
        $request->session()->forget('pending_verification_user_id');

        return redirect()->route('login')
            ->with('status', 'Email verified successfully! You can now sign in.');
    }

    /** Resend a fresh OTP */
    public function resend(Request $request): RedirectResponse
    {
        $userId = $request->session()->get('pending_verification_user_id');
        if (! $userId) {
            return redirect()->route('register');
        }

        $user = User::find($userId);
        if (! $user) {
            return redirect()->route('register');
        }

        $recent = OtpVerification::where('user_id', $userId)
            ->where('created_at', '>=', Carbon::now()->subMinute())
            ->exists();

        if ($recent) {
            return back()->withErrors(['code' => 'Please wait at least 1 minute before requesting a new code.']);
        }

        OtpVerification::where('user_id', $userId)->delete();
        self::sendOtp($user);

        $channels = ['your email'];
        if (config('services.whatsapp.enabled') && $user->candidateProfile?->phone) {
            $channels[] = 'WhatsApp';
        }

        return back()->with('status', 'A new verification code has been sent via ' . implode(' and ', $channels) . '.');
    }

    /** Generate OTP, store it, send via email + WhatsApp (if configured) */
    public static function sendOtp(User $user): void
    {
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpVerification::create([
            'user_id'    => $user->id,
            'code'       => $code,
            'expires_at' => Carbon::now()->addMinutes(15),
        ]);

        // Always send email
        Mail::to($user->email)->send(new OtpVerificationMail($user->name, $code));

        // Also send via WhatsApp if enabled and user already has a phone on profile
        if (config('services.whatsapp.enabled')) {
            $phone = $user->candidateProfile?->phone ?? null;
            if ($phone) {
                app(WhatsAppService::class)->sendOtp($phone, $user->name, $code);
            }
        }
    }
}
