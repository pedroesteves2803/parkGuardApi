<?php

namespace App\Notifications;

use App\Mail\PasswordResetMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Src\Administration\Domain\Entities\PasswordResetToken;

class PasswordResetNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $passwordResetToken;

    public function __construct(PasswordResetToken $passwordResetToken)
    {
        $this->passwordResetToken = $passwordResetToken;
    }


    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable)
    {
        return (new PasswordResetMail($this->passwordResetToken));
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
