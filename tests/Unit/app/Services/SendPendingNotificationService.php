<?php

use App\Services\SendPendingNotificationService;
use App\Notifications\VehiclePendingNotification;
use Src\Vehicles\Domain\Entities\Vehicle;
use Illuminate\Support\Facades\Notification;

it('sending notification of pending test', function () {
    $vehicle = mock(Vehicle::class);

    Notification::fake();

    $service = new SendPendingNotificationService();

    $service->execute($vehicle);

    Notification::assertSentTo(
        [['mail' => 'teste@teste.com', 'vonage' => '5511935051520']],
        VehiclePendingNotification::class,
        function ($notification, $channels, $notifiable) use ($vehicle) {
            return $notification->vehicle === $vehicle;
        }
    );
});
