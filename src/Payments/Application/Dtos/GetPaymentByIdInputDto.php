<?php

namespace Src\Payments\Application\Dtos;

final readonly class GetPaymentByIdInputDto
{
    public function __construct(
        public int $id,
    ) {
    }
}
