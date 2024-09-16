<?php

namespace App\Services;

use App\Notifications\VehiclePendingNotification;
use Src\Vehicles\Domain\Services\ISendPendingNotificationService;
use Illuminate\Support\Facades\Notification;
use Src\Vehicles\Domain\Entities\Vehicle;

class SendPendingNotificationService implements ISendPendingNotificationService
{
    public function __construct()
    {}

    public function execute(Vehicle $vehicle): void
    {
        Notification::route('vonage', '5511935051520')
            ->notify(new VehiclePendingNotification($vehicle));
    }
}
