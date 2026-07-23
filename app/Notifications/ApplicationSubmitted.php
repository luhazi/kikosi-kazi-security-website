<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public readonly Application $application)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Application Received — {$this->application->job->title}")
            ->greeting("Hello {$notifiable->name}!")
            ->line("Thank you for applying for the position of {$this->application->job->title} at Kikosi Kazi Security.")
            ->line('We have received your application and our HR team will review it shortly.')
            ->line('You can track your application status in your candidate portal.')
            ->action('View Application', route('candidate.applications.show', $this->application))
            ->line('We will be in touch within 2 weeks. Thank you for your interest in joining our team.');
    }
}
