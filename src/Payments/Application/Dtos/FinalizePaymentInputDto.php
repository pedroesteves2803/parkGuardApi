<?php

namespace Src\Payments\Application\Dtos;

final readonly class FinalizePaymentInputDto
{
    public function __construct(
        public int $id,
    ) {
    }
}
