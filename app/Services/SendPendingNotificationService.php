<?php

namespace App\Services;

use App\Notifications\VehiclePendingNotification;
use DateTime;
use Src\Vehicles\Domain\Services\ISendPendingNotificationService;
use Illuminate\Support\Facades\Notification;
use Src\Vehicles\Domain\Entities\Vehicle;

class SendPendingNotificationService implements ISendPendingNotificationService
{

    public function __construct(
        readonly Notification $notify
    )
    {}

    public function execute(Vehicle $vehicle): void
    {

        $this->notify::route('mail', 'teste@teste.com')->notify(
                (new VehiclePendingNotification($vehicle))
            );
    }

}
