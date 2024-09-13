<?php

namespace Src\Payments\Application\Dtos;

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
