<?php

namespace App\Services;

use App\Notifications\passwordResetNotification;
use Illuminate\Support\Facades\Notification;
use Src\Administration\Domain\Entities\PasswordResetToken;
use Src\Administration\Domain\Services\ISendPasswordResetTokenService;

class SendPasswordResetTokenService implements ISendPasswordResetTokenService
{
    public function __construct()
    {}

    public function execute(PasswordResetToken $passwordResetToken): void
    {
        Notification::route('mail', 'teste@teste.com')
            ->notify(new PasswordResetNotification($passwordResetToken));
    }
}
