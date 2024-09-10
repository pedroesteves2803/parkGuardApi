<?php

namespace Src\Payments\Application\Payment\Dtos;

use Src\Shared\Utils\Notification;

final class CalculateValueOutputDto
{
    public function __construct(
        public ?int $totalToPay,
        public Notification $notification
    ) {
    }
}
