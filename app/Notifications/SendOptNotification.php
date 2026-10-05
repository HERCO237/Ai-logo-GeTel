<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendOptNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private string $otp,
        private string $type
    ) { }

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

        if($this->type === 'register') {
            $subject = 'Vérification de votre compte';
            $title = 'Vérification de votre adresse e-mail';
        } else{
            $subject = 'Réinitialisation de votre mot de passe';
            $title = 'Réinitialisation de votre mot de passe';
        }
        return (new MailMessage)
            ->subject($subject)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line($title)
            ->line('Your OTP code is: ' . $this->otp)
            ->line('This code will expire in 10 minutes.')
            ->line('Si vous n êtes pas à l origine de cette demande, ignorez cet e-mail.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
