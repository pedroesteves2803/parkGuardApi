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

    /**
     * Create a new notification instance.
     */
    public function __construct(
        readonly Vehicle $vehicle
    )
    {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'vonage'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): VehiclePendingEmail
    {
        return new VehiclePendingEmail($this->vehicle);
    }

    public function toVonage(object $notifiable): VonageMessage
    {
        return (new VonageMessage)
            ->content("Veículo com a placa: {$this->vehicle->licensePlate->value()} possui uma pendência.");
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
