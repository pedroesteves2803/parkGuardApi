<?php

namespace Src\Payments\Application\Payment\Dtos;

final class GetPaymentByIdInputDto
{
    public function __construct(
        readonly int $id,
    ) {
    }
}
