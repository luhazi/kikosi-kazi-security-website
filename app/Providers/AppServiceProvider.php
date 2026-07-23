<?php

namespace App\Providers;

use App\Channels\WhatsAppChannel;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Register custom WhatsApp notification channel
        Notification::resolved(function (ChannelManager $service) {
            $service->extend('whatsapp', fn ($app) => new WhatsAppChannel());
        });
    }
}
