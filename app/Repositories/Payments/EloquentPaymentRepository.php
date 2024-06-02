<?php

namespace App\Repositories\Payments;

use App\Models\Payment as ModelsPayment;
use Illuminate\Support\Collection;
use Src\Payments\Domain\Entities\Payment;
use Src\Payments\Domain\Repositories\IPaymentRepository;
use Src\Payments\Domain\ValueObjects\DateTime;
use Src\Payments\Domain\ValueObjects\PaymentMethod;
use Src\Payments\Domain\ValueObjects\Value;

final class EloquentPaymentRepository implements IPaymentRepository
{
    // public function getAll(): ?Collection {

    // }

    // public function getById(int $id): ?Payment {

    // }

    public function create(Payment $payment): Payment
    {
       $modelsPayment = new ModelsPayment();

       $modelsPayment->value = $payment->value->value();
       $modelsPayment->date_time = $payment->dateTime->value();
       $modelsPayment->payment_method = $payment->paymentMethod->value();
       $modelsPayment->vehicle_id = $payment->vehicle->id;
       $modelsPayment->value = $payment->value->value();
       $modelsPayment->save();

       return new Payment(
        $modelsPayment->id,
        new Value($modelsPayment->value),
        new DateTime($modelsPayment->date_time),
        new PaymentMethod($modelsPayment->payment_method),
        $payment->vehicle,
       );
    }

    // public function update(Payment $payment): Payment{

    // }

    // public function delete(int $id): void{

    // }
}
