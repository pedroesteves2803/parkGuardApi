<?php

namespace Src\Payments\Application\Payment\Dtos;

final readonly class DeletePaymentInputDto
{
    public function __construct(
        public int $id,
    ) {
    }
}
