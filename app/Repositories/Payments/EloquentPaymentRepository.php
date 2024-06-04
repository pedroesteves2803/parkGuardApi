<?php

namespace App\Repositories\Payments;

use App\Models\Payment as ModelsPayment;
use App\Models\Vehicle as ModelsVehicle;
use Illuminate\Support\Collection;
use Src\Payments\Domain\Entities\Payment;
use Src\Payments\Domain\Repositories\IPaymentRepository;
use Src\Payments\Domain\ValueObjects\PaymentMethod;
use Src\Payments\Domain\ValueObjects\RegistrationTime;
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
    public function getAll(): ?Collection
    {
        $payments = ModelsPayment::all();

        $payments = $payments->map(function ($payment) {
            $modelsVehicle = ModelsVehicle::find($payment->vehicle_id);

            $vehicle = new Vehicle(
                $modelsVehicle->id,
                is_null($modelsVehicle->manufacturer) ? null : new Manufacturer($modelsVehicle->manufacturer),
                is_null($modelsVehicle->color) ? null : new Color($modelsVehicle->color),
                is_null($modelsVehicle->model) ? null : new Model($modelsVehicle->model),
                new LicensePlate($modelsVehicle->license_plate),
                new EntryTimes($modelsVehicle->entry_times),
                is_null($modelsVehicle->departure_times) ? null : new DepartureTimes($modelsVehicle->departure_times)
            );

            return new Payment(
                $payment->id,
                is_null($payment->value) ? null : new Value($payment->value),
                is_null($payment->registration_time) ? null : new RegistrationTime($payment->registration_time),
                is_null($payment->payment_method) ? null : new PaymentMethod($payment->payment_method),
                is_null($payment->paid) ? null : $payment->paid,
                $vehicle
            );
        });

        return $payments;
    }

    public function getById(int $id): ?Payment
    {
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
            is_null($modelsVehicle->color) ? null : new Color($modelsVehicle->color),
            is_null($modelsVehicle->model) ? null : new Model($modelsVehicle->model),
            new LicensePlate($modelsVehicle->license_plate),
            new EntryTimes($modelsVehicle->entry_times),
            is_null($modelsVehicle->departure_times) ? null : new DepartureTimes($modelsVehicle->departure_times)
        );

        return new Payment(
            $modelsPayment->id,
            is_null($modelsPayment->value) ? null : new Value($modelsPayment->value),
            is_null($modelsPayment->registration_time) ? null : new RegistrationTime($modelsPayment->registration_time),
            is_null($modelsPayment->payment_method) ? null : new PaymentMethod($modelsPayment->payment_method),
            is_null($modelsPayment->paid) ? null : $modelsPayment->paid,
            $vehicle
        );
    }

    public function create(Payment $payment): Payment
    {
        $modelsPayment = new ModelsPayment();

        $modelsPayment->value = $payment->value()->value();
        $modelsPayment->registration_time = $payment->registrationTime()->value();
        $modelsPayment->payment_method = $payment->paymentMethod()->value();
        $modelsPayment->paid = $payment->paid();
        $modelsPayment->vehicle_id = $payment->vehicle()->id();
        $modelsPayment->save();

        return new Payment(
            $modelsPayment->id,
            new Value($modelsPayment->value),
            new RegistrationTime($modelsPayment->registration_time),
            new PaymentMethod($modelsPayment->payment_method),
            $modelsPayment->paid,
            $payment->vehicle(),
        );
    }

    public function finalize(Payment $payment): ?Payment
    {
        $modelsPayment = ModelsPayment::where([
            'id' => $payment->id(),
            'paid' => false,
        ])->first();

        if (is_null($modelsPayment)) {
            return null;
        }

        $modelsPayment->paid = true;
        $modelsPayment->save();

        return new Payment(
            $modelsPayment->id,
            new Value($modelsPayment->value),
            new RegistrationTime($modelsPayment->registration_time),
            new PaymentMethod($modelsPayment->payment_method),
            $modelsPayment->paid,
            $payment->vehicle(),
        );
    }

    public function delete(int $id): void
    {
        $modelsPayment = ModelsPayment::find($id);
        $modelsPayment->delete();
    }
}
