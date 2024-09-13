<?php

namespace Src\Payments\Application\Dtos;

use Src\Payments\Domain\Entities\Payment;
use Src\Shared\Utils\Notification;

final readonly class FinalizePaymentOutputDto
{
    public function __construct(
        public ?Payment     $payment,
        public Notification $notification
    ) {
    }
}
