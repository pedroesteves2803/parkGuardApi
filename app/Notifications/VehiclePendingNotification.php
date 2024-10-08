<?php

namespace App\Notifications;

use App\Mail\VehiclePendingEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Src\Vehicles\Domain\Entities\Vehicle;
use Illuminate\Notifications\Messages\VonageMessage;

class VehiclePendingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $vehicle;

    public function __construct(Vehicle $vehicle)
    {
        $this->vehicle = $vehicle;
    }

    public function via($notifiable): array
    {
        return ['mail', 'vonage'];
    }

    public function toMail($notifiable): VehiclePendingEmail
    {
        return new VehiclePendingEmail($this->vehicle);
    }

    public function toVonage($notifiable): VonageMessage
    {
        return (new VonageMessage)
            ->content("Veiculo com a placa: {$this->vehicle->licensePlate()->value()} possui uma pendencia.");
    }

    public function toArray($notifiable): array
    {
        return [
            'vehicle_id' => $this->vehicle->id(),
            'license_plate' => $this->vehicle->licensePlate()->value(),
        ];
    }
}
