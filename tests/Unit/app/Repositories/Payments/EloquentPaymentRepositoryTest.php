<?php

use App\Models\Payment as ModelsPayment;
use App\Models\Vehicle as ModelsVehicle;
use App\Repositories\Payments\EloquentPaymentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Src\Payments\Domain\Entities\Payment;
use Src\Payments\Domain\ValueObjects\PaymentMethod;
use Src\Payments\Domain\ValueObjects\RegistrationTime;
use Src\Payments\Domain\ValueObjects\Value;
use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\ValueObjects\Color;
use Src\Vehicles\Domain\ValueObjects\EntryTimes;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Manufacturer;
use Src\Vehicles\Domain\ValueObjects\Model;
use Src\Shared\Utils\Notification;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('can get all payments', function () {
    $vehicleOne = ModelsVehicle::factory()->create([
        'manufacturer' => 'Honda',
        'color' => 'Azul',
        'model' => 'Civic',
        'license_plate' => 'DGF1798',
        'entry_times' => new DateTime(),
        'departure_times' => null,
    ]);

    $vehicleTwo = ModelsVehicle::factory()->create([
        'manufacturer' => 'Honda',
        'color' => 'Azul',
        'model' => 'Civic',
        'license_plate' => 'DGV1798',
        'entry_times' => new DateTime(),
        'departure_times' => null,
    ]);

    ModelsPayment::factory()->create([
        'value'     => 1000,
        'registration_time'    => now(),
        'payment_method' => 1,
        'paid'     => false,
        'vehicle_id'     => $vehicleOne->id,
    ]);

    ModelsPayment::factory()->create([
        'value'     => 2000,
        'registration_time'    => now(),
        'payment_method' => 1,
        'paid'     => false,
        'vehicle_id'     => $vehicleTwo->id,
    ]);

    $repository = new EloquentPaymentRepository();
    $payments = $repository->getAll();

    expect($payments)->toBeInstanceOf(Collection::class)
        ->and($payments)->toHaveCount(2);
});

it('can get payment by id', function () {
    $vehicle = ModelsVehicle::factory()->create([
        'manufacturer' => 'Honda',
        'color' => 'Azul',
        'model' => 'Civic',
        'license_plate' => 'DGF1798',
        'entry_times' => new DateTime(),
        'departure_times' => null,
    ]);

    $payment = ModelsPayment::factory()->create([
        'value'     => 2000,
        'registration_time'    => now(),
        'payment_method' => 1,
        'paid'     => false,
        'vehicle_id'     => $vehicle->id,
    ]);

    $repository = new EloquentPaymentRepository();

    $retrievedPayment = $repository->getById($payment->id);

    expect($retrievedPayment)->toBeInstanceOf(Payment::class)
        ->and($retrievedPayment->id())->toBe($payment->id)
        ->and($retrievedPayment->value()->value())->toBe($payment->value)
        ->and($retrievedPayment->registrationTime()->value()->format('Y-m-d H:i:s'))->toBe($payment->registration_time->format('Y-m-d H:i:s'))
        ->and($retrievedPayment->paymentMethod()->value())->toBe($payment->payment_method)
        ->and($retrievedPayment->paid())->toBe($payment->paid)
        ->and($retrievedPayment->vehicle()->id())->toBe($payment->vehicle_id);

});

it('create a new payment', function () {
    $vehicle = ModelsVehicle::factory()->create([
        'manufacturer' => 'Honda',
        'color' => 'Azul',
        'model' => 'Civic',
        'license_plate' => 'DGF1798',
        'entry_times' => new DateTime(),
        'departure_times' => null,
    ]);

    $vehicleData = new Vehicle(
        $vehicle->id,
        new Manufacturer($vehicle->manufacturer),
        new Color($vehicle->color),
        new Model($vehicle->model),
        new LicensePlate($vehicle->license_plate),
        new EntryTimes($vehicle->entry_times),
        $vehicle->departure_times
    );

    $paymentData = new Payment(
        null,
        new Value(1000),
        new RegistrationTime(now()),
        new PaymentMethod(1),
        false,
        $vehicleData,
        New Notification()
    );
    $repository = new EloquentPaymentRepository();
    $createdPayment = $repository->create($paymentData);

    expect($createdPayment)->toBeInstanceOf(Payment::class)
        ->and($createdPayment->id())->not()->toBeNull()
        ->and($createdPayment->value()->value())->toBe(1000)
        ->and($createdPayment->registrationTime()->value())->not()->toBeNull()
        ->and($createdPayment->paymentMethod()->value())->toBe(1)
        ->and($createdPayment->paid())->toBe(false)
        ->and($createdPayment->vehicle()->id())->toBe(1);
});

it('finalize a vehicle', function () {
    $vehicle = ModelsVehicle::factory()->create([
        'manufacturer' => 'Honda',
        'color' => 'Azul',
        'model' => 'Civic',
        'license_plate' => 'DGF1798',
        'entry_times' => new DateTime(),
        'departure_times' => null,
    ]);

    $vehicleData = new Vehicle(
        $vehicle->id,
        new Manufacturer($vehicle->manufacturer),
        new Color($vehicle->color),
        new Model($vehicle->model),
        new LicensePlate($vehicle->license_plate),
        new EntryTimes($vehicle->entry_times),
        $vehicle->departure_times
    );

    $payment = ModelsPayment::factory()->create([
        'value'     => 1000,
        'registration_time'    => now(),
        'payment_method' => 1,
        'paid'     => false,
        'vehicle_id'     => $vehicleData->id(),
    ]);

    $paymentData = new Payment(
        $payment->id,
        new Value($payment->value),
        new RegistrationTime($payment->registration_time),
        new PaymentMethod($payment->payment_method),
        $payment->paid,
        $vehicleData,
        New Notification()
    );


    $repository = new EloquentPaymentRepository();
    $finalizePayment = $repository->finalize($paymentData);


    expect($finalizePayment)->toBeInstanceOf(Payment::class)
        ->and($finalizePayment->id())->not()->toBeNull()
        ->and($finalizePayment->value()->value())->toBe(1000)
        ->and($finalizePayment->registrationTime()->value())->not()->toBeNull()
        ->and($finalizePayment->paymentMethod()->value())->toBe(1)
        ->and($finalizePayment->paid())->toBe(true)
        ->and($finalizePayment->vehicle()->id())->toBe(1);
});

it('delete a vehicle', function () {
    $vehicle = ModelsVehicle::factory()->create([
        'manufacturer' => 'Honda',
        'color' => 'Azul',
        'model' => 'Civic',
        'license_plate' => 'DGF1798',
        'entry_times' => new DateTime(),
        'departure_times' => null,
    ]);

    $vehicleData = new Vehicle(
        $vehicle->id,
        new Manufacturer($vehicle->manufacturer),
        new Color($vehicle->color),
        new Model($vehicle->model),
        new LicensePlate($vehicle->license_plate),
        new EntryTimes($vehicle->entry_times),
        $vehicle->departure_times
    );

    $payment = ModelsPayment::factory()->create([
        'value'     => 1000,
        'registration_time'    => now(),
        'payment_method' => 1,
        'paid'     => false,
        'vehicle_id'     => $vehicleData->id(),
    ]);

    $paymentData = new Payment(
        $payment->id,
        new Value($payment->value),
        new RegistrationTime($payment->registration_time),
        new PaymentMethod($payment->payment_method),
        $payment->paid,
        $vehicleData,
        New Notification()
    );

    $repository = new EloquentPaymentRepository();
    $repository->delete($paymentData->id());
    $deletePayment = $repository->getById(1);
    expect($deletePayment)->toBeNull();
    $this->assertNull($deletePayment);
});
