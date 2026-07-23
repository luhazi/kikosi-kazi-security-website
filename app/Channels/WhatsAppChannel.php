<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;

/**
 * Custom Laravel notification channel for WhatsApp.
 *
 * Registered in AppServiceProvider.
 * Calls $notification->toWhatsapp($notifiable) on the notification class.
 */
class WhatsAppChannel
{
    public function send(mixed $notifiable, Notification $notification): void
    {
        if (! method_exists($notification, 'toWhatsapp')) {
            return;
        }

        $notification->toWhatsapp($notifiable);
    }
}
