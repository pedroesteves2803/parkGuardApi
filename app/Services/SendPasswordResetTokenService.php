<?php

namespace App\Services;

use App\Notifications\passwordResetNotification;
use App\Notifications\VehiclePendingNotification;
use Src\Vehicles\Domain\Services\ISendPendingNotificationService;
use Illuminate\Support\Facades\Notification;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\Entities\PasswordResetToken;
use Src\Administration\Domain\Services\ISendPasswordResetTokenService;
use Src\Vehicles\Domain\Entities\Vehicle;

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
