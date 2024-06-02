<?php

namespace Src\Payments\Domain\Repositories;

use Src\Payments\Domain\Entities\Payment;

interface IPaymentRepository
{
    // public function getAll(): ?Collection;

    // public function getById(int $id): ?Payment;

    public function create(Payment $payment): Payment;

    // public function update(Payment $payment): Payment;

    // public function delete(int $id): void;
}
