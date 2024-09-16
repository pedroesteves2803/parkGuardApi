<?php

namespace App\Repositories\Payments;

use App\Models\Payment as ModelsPayment;
use App\Models\Vehicle as ModelsVehicle;
use Illuminate\Support\Collection;
use Src\Payments\Domain\Entities\Payment;
use Src\Payments\Domain\Factory\PaymentFactory;
use Src\Payments\Domain\Repositories\IPaymentRepository;
use Src\Payments\Domain\ValueObjects\PaymentMethod;
use Src\Payments\Domain\ValueObjects\RegistrationTime;
use Src\Payments\Domain\ValueObjects\Value;
use Src\Shared\Utils\Notification;
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
        return ModelsPayment::orderBy('id', 'desc')->get()->map(function ($payment) {
            $modelsVehicle = ModelsVehicle::find($payment->vehicle_id);

            return $this->createPaymentFromModels($modelsVehicle, $payment);
        });
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

        return $this->createPaymentFromModels($modelsVehicle, $modelsPayment);
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

        return (new PaymentFactory())->create(
            $modelsPayment->id,
            $modelsPayment->value,
            $modelsPayment->registration_time,
            $modelsPayment->payment_method,
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

        return (new PaymentFactory())->create(
            $modelsPayment->id,
            $modelsPayment->value,
            $modelsPayment->registration_time,
            $modelsPayment->payment_method,
            $modelsPayment->paid,
            $payment->vehicle(),
        );
    }

    public function delete(int $id): void
    {
        $modelsPayment = ModelsPayment::find($id);
        $modelsPayment->delete();
    }


    public function createPaymentFromModels(ModelsVehicle $modelsVehicle, ModelsPayment $payment): Payment
    {
        $vehicle = new Vehicle(
            $modelsVehicle->id,
            is_null($modelsVehicle->manufacturer) ? null : new Manufacturer($modelsVehicle->manufacturer),
            is_null($modelsVehicle->color) ? null : new Color($modelsVehicle->color),
            is_null($modelsVehicle->model) ? null : new Model($modelsVehicle->model),
            new LicensePlate($modelsVehicle->license_plate),
            new EntryTimes($modelsVehicle->entry_times),
            is_null($modelsVehicle->departure_times) ? null : new DepartureTimes($modelsVehicle->departure_times)
        );

        return (new PaymentFactory())->create(
            $payment->id,
            is_null($payment->value) ? null : $payment->value,
            is_null($payment->registration_time) ? null : $payment->registration_time,
            is_null($payment->payment_method) ? null : $payment->payment_method,
            is_null($payment->paid) ? null : $payment->paid,
            $vehicle,
        );
    }

}
