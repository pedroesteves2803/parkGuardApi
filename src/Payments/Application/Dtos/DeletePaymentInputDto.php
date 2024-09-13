<?php

namespace Src\Payments\Application\Dtos;

final readonly class DeletePaymentInputDto
{
    public function __construct(
        public int $id,
    ) {
    }
}
