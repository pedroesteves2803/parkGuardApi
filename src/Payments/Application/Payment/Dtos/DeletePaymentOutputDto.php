<?php

namespace Src\Payments\Application\Payment\Dtos;

use Src\Payments\Domain\Entities\Payment;
use Src\Shared\Utils\Notification;

final readonly class DeletePaymentOutputDto
{
    public function __construct(
        public ?Payment     $payment,
        public Notification $notification
    ) {
    }
}
