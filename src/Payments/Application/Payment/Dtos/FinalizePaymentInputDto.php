<?php

namespace Src\Payments\Application\Payment\Dtos;

final readonly class FinalizePaymentInputDto
{
    public function __construct(
        public int $id,
    ) {
    }
}
