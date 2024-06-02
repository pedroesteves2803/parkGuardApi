<?php

namespace Src\Payments\Application\Payment\Dtos;

use DateTime;

final class CreatePaymentInputDto
{
    public function __construct(
        readonly string $value,
        readonly DateTime $dateTime,
        readonly int $paymentMethod,
        readonly int $vehicle_id,
    ) {
    }
}
