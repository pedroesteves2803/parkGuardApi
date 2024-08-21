<?php

namespace Src\Payments\Application\Payment\Dtos;

final readonly class GetPaymentByIdInputDto
{
    public function __construct(
        public int $id,
    ) {
    }
}
