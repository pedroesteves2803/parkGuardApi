<?php

namespace App\Repositories\Payments;

use App\Models\Vehicle as ModelsVehicle;
use App\Models\Payment as ModelsPayment;
use Src\Payments\Domain\Entities\Payment;
use Src\Payments\Domain\Repositories\IPaymentRepository;
use Src\Payments\Domain\ValueObjects\DateTime;
use Src\Payments\Domain\ValueObjects\PaymentMethod;
use Src\Payments\Domain\ValueObjects\Value;
use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\ValueObjects\Color;
use Src\Vehicles\Domain\ValueObjects\DepartureTimes;
use Src\Vehicles\Domain\ValueObjects\EntryTimes;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Manufacturer;
use Src\Vehicles\Domain\ValueObjects\Model;

final class EloquentPaymentRepository implements IPaymentRepository
{
    // public function getAll(): ?Collection {

    // }

    public function getById(int $id): ?Payment {
        $modelsPayment = ModelsPayment::find($id);

        if (is_null($modelsPayment)) {
            return null;
        }

        $modelsVehicle = ModelsVehicle::find($id);

        if (is_null($modelsVehicle)) {
            return null;
        }

        $vehicle = new Vehicle(
            $modelsVehicle->id,
            is_null($modelsVehicle->manufacturer) ? null : new Manufacturer($modelsVehicle->manufacturer),
            is_null($modelsVehicle->color) ? null :  new Color($modelsVehicle->color),
            is_null($modelsVehicle->model) ? null :  new Model($modelsVehicle->model),
            new LicensePlate($modelsVehicle->license_plate),
            new EntryTimes($modelsVehicle->entry_times),
            is_null($modelsVehicle->departure_times) ? null : new DepartureTimes($modelsVehicle->departure_times)
        );

        return new Payment(
            $modelsPayment->id,
            is_null($modelsPayment->value) ? null : new Value($modelsPayment->value),
            is_null($modelsPayment->date_time) ? null : new DateTime($modelsPayment->value),
            is_null($modelsPayment->payment_method) ? null : new PaymentMethod($modelsPayment->payment_method),
            is_null($modelsPayment->paid) ? null : $modelsPayment->paid,
            $vehicle
        );
    }

    public function create(Payment $payment): Payment
    {
       $modelsPayment = new ModelsPayment();

       $modelsPayment->value = $payment->value()->value();
       $modelsPayment->date_time = $payment->dateTime()->value();
       $modelsPayment->payment_method = $payment->paymentMethod()->value();
       $modelsPayment->paid = $payment->paid();
       $modelsPayment->vehicle_id = $payment->vehicle()->id();
       $modelsPayment->save();

       return new Payment(
        $modelsPayment->id,
        new Value($modelsPayment->value),
        new DateTime($modelsPayment->date_time),
        new PaymentMethod($modelsPayment->payment_method),
        $modelsPayment->paid,
        $payment->vehicle(),
       );
    }

    // public function update(Payment $payment): Payment{

    // }

    public function delete(int $id): void
    {
        $modelsPayment = ModelsPayment::find($id);
        $modelsPayment->delete();
    }
}
