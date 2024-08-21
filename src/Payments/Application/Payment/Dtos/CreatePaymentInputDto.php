<?php

namespace Src\Payments\Application\Payment\Dtos;

use DateTime;

final readonly class CreatePaymentInputDto
{
    public function __construct(
        public DateTime $dateTime,
        public int      $paymentMethod,
        public int      $vehicle_id,
    ) {
    }
}
