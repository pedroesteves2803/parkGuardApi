<?php

namespace Src\Payments\Application\Payment\Dtos;

final class FinalizePaymentInputDto
{
    public function __construct(
        readonly int $id,
    ) {
    }
}
