<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TwoFactorCodeNotification extends Notification
{
    protected string $code;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $code)
    {
        $this->code = $code;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $expiryMinutes = config('auth.two_factor.code_expiry', 300) / 60;
        
        return (new MailMessage)
            ->subject('Your Login Verification Code - ' . config('app.name'))
            ->view('emails.two-factor-code', [
                'code' => $this->code,
                'user' => $notifiable,
                'expiryMinutes' => $expiryMinutes,
            ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'code_sent' => true,
            'sent_at' => now(),
        ];
    }
}
