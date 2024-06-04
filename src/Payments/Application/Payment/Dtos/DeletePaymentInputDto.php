<?php

namespace Src\Payments\Application\Payment\Dtos;

use DateTime;

final class DeletePaymentInputDto
{
    public function __construct(
        readonly int $id,
    ) {
    }
}
