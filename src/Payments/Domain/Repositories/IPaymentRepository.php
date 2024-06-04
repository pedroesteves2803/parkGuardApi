<?php

namespace Src\Payments\Domain\Repositories;

use Illuminate\Support\Collection;
use Src\Payments\Domain\Entities\Payment;

interface IPaymentRepository
{
    public function getAll(): ?Collection;

    public function getById(int $id): ?Payment;

    public function create(Payment $payment): Payment;

    public function finalize(Payment $payment): ?Payment;

    public function delete(int $id): void;
}
