<?php

namespace App\Notifications;

use App\Models\Application;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Application $application) {}

    public function via(object $notifiable): array
    {
        $channels = ['mail'];

        if (config('services.whatsapp.enabled') && $notifiable->candidateProfile?->phone) {
            $channels[] = 'whatsapp';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $label = Application::STATUS_LABELS[$this->application->status] ?? $this->application->status;

        $mail = (new MailMessage)
            ->subject("Update on Your Application — {$this->application->job->title}")
            ->greeting("Hello {$notifiable->name}!")
            ->line("There is an update on your application for {$this->application->job->title}.")
            ->line("Your application status is now: **{$label}**");

        if ($this->application->status === 'interview_scheduled') {
            $mail->line('Please log in to your candidate portal to view your interview details and confirm attendance.');
        }

        if ($this->application->status === 'successful') {
            $mail->line('Congratulations! Please await further communication from our HR team regarding your onboarding.');
        }

        if ($this->application->status === 'rejected') {
            $mail->line('Thank you for your interest. We encourage you to apply for future opportunities.');
        }

        return $mail->action('View My Application', route('candidate.applications.show', $this->application));
    }

    /**
     * WhatsApp delivery — called by the custom WhatsApp channel.
     */
    public function toWhatsapp(object $notifiable): void
    {
        if (! config('services.whatsapp.enabled')) {
            return;
        }

        $phone = $notifiable->candidateProfile?->phone ?? null;
        if (! $phone) {
            return;
        }

        $wa       = app(WhatsAppService::class);
        $label    = Application::STATUS_LABELS[$this->application->status] ?? $this->application->status;
        $appUrl   = route('candidate.applications.show', $this->application);
        $jobTitle = $this->application->job->title;

        match ($this->application->status) {
            'interview_scheduled' => $wa->sendInterviewScheduled($phone, $notifiable->name, $jobTitle, $appUrl),
            'successful'          => $wa->sendOfferNotification($phone, $notifiable->name, $jobTitle, $appUrl),
            default               => $wa->sendApplicationStatusUpdate($phone, $notifiable->name, $jobTitle, $label, $appUrl),
        };
    }
}
