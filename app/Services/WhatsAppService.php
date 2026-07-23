<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * WhatsApp Cloud API (Meta) Service
 *
 * Docs: https://developers.facebook.com/docs/whatsapp/cloud-api/messages
 *
 * Required .env keys:
 *   WHATSAPP_TOKEN=      # Meta User Access Token or System User Token
 *   WHATSAPP_PHONE_ID=   # WhatsApp Business Phone Number ID (from Meta Developer console)
 *   WHATSAPP_ENABLED=true
 */
class WhatsAppService
{
    private const API_VERSION = 'v19.0';
    private const BASE_URL    = 'https://graph.facebook.com';

    private string $token;
    private string $phoneId;
    private bool   $enabled;

    public function __construct()
    {
        $this->token   = config('services.whatsapp.token', '');
        $this->phoneId = config('services.whatsapp.phone_id', '');
        $this->enabled = config('services.whatsapp.enabled', false);
    }

    /**
     * Send a plain text WhatsApp message.
     *
     * Per Meta docs (developers.facebook.com/docs/whatsapp/cloud-api/messages/text-messages):
     *   { messaging_product, recipient_type, to, type:"text", text:{ preview_url, body } }
     *
     * @param  string  $to    Recipient phone (E.164 or local TZ format)
     * @param  string  $body  Message text (max 4096 chars)
     */
    public function sendText(string $to, string $body): bool
    {
        if (! $this->enabled || ! $this->token || ! $this->phoneId) {
            Log::debug('WhatsApp: disabled or not configured', ['to' => $to]);
            return false;
        }

        $phone = $this->normalizePhone($to);
        if (! $phone) {
            Log::warning('WhatsApp: invalid phone number', ['to' => $to]);
            return false;
        }

        try {
            $response = Http::withToken($this->token)
                ->timeout(10)
                ->post($this->url(), [
                    'messaging_product' => 'whatsapp',
                    'recipient_type'    => 'individual',
                    'to'                => $phone,
                    'type'              => 'text',
                    'text'              => [
                        'preview_url' => false,
                        'body'        => $body,
                    ],
                ]);

            if ($response->successful()) {
                Log::info('WhatsApp: message sent', ['to' => $phone]);
                return true;
            }

            Log::error('WhatsApp: API error', [
                'to'     => $phone,
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            return false;
        } catch (\Throwable $e) {
            Log::error('WhatsApp: exception', ['to' => $phone, 'error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * OTP verification code — sent when candidate registers or requests resend.
     */
    public function sendOtp(string $to, string $name, string $code): bool
    {
        $msg = "🔐 *Kikosi Kazi — Verification Code*\n\n"
             . "Hello {$name},\n\n"
             . "Your account verification code is:\n\n"
             . "     *{$code}*\n\n"
             . "This code expires in *15 minutes*. Do not share it with anyone.\n\n"
             . "_If you did not create an account, please ignore this message._";

        return $this->sendText($to, $msg);
    }

    /**
     * General application status update (under review, shortlisted, rejected, etc.)
     */
    public function sendApplicationStatusUpdate(
        string $to,
        string $candidateName,
        string $jobTitle,
        string $statusLabel,
        string $appUrl
    ): bool {
        $msg = "📋 *Kikosi Kazi — Application Update*\n\n"
             . "Hello {$candidateName},\n\n"
             . "Your application for *{$jobTitle}* has been updated.\n\n"
             . "New status: *{$statusLabel}*\n\n"
             . "View your application:\n{$appUrl}\n\n"
             . "_Kikosi Kazi Security_";

        return $this->sendText($to, $msg);
    }

    /**
     * Interview invitation — candidate has been shortlisted for interview.
     */
    public function sendInterviewScheduled(
        string $to,
        string $candidateName,
        string $jobTitle,
        string $appUrl
    ): bool {
        $msg = "🗓️ *Kikosi Kazi — Interview Invitation*\n\n"
             . "Congratulations {$candidateName}!\n\n"
             . "You have been shortlisted for an interview for the position of *{$jobTitle}*.\n\n"
             . "Please log in to your candidate portal to view the interview details and confirm your attendance:\n"
             . "{$appUrl}\n\n"
             . "_Kikosi Kazi Security_";

        return $this->sendText($to, $msg);
    }

    /**
     * Job offer / successful — candidate has been selected for the job.
     */
    public function sendOfferNotification(
        string $to,
        string $candidateName,
        string $jobTitle,
        string $appUrl
    ): bool {
        $msg = "🎉 *Kikosi Kazi — Job Offer*\n\n"
             . "Dear {$candidateName},\n\n"
             . "We are pleased to inform you that your application for *{$jobTitle}* has been *successful*!\n\n"
             . "You have been selected for this position. Our HR team will contact you shortly regarding onboarding and next steps.\n\n"
             . "Log in to your portal for more details:\n{$appUrl}\n\n"
             . "_Kikosi Kazi Security_";

        return $this->sendText($to, $msg);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function url(): string
    {
        return sprintf('%s/%s/%s/messages', self::BASE_URL, self::API_VERSION, $this->phoneId);
    }

    /**
     * Normalize phone to E.164 — strips spaces/dashes, adds +255 for local TZ numbers.
     */
    private function normalizePhone(string $phone): ?string
    {
        $p = preg_replace('/[\s\-\(\)]/', '', $phone);

        // Already E.164
        if (preg_match('/^\+\d{9,15}$/', $p)) {
            return ltrim($p, '+'); // API wants without leading +
        }

        // International without +
        if (preg_match('/^\d{10,15}$/', $p)) {
            return $p;
        }

        // Tanzania local: 07xx / 06xx → 255xxx
        if (preg_match('/^0([67]\d{8})$/', $p, $m)) {
            return '255' . $m[1];
        }

        // Tanzania with 255
        if (preg_match('/^255[67]\d{8}$/', $p)) {
            return $p;
        }

        return null; // invalid
    }
}
