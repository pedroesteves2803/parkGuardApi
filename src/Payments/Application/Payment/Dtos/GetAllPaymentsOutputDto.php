<?php

namespace Src\Payments\Application\Payment\Dtos;

use Illuminate\Support\Collection;
use Src\Shared\Utils\Notification;

final readonly class GetAllPaymentsOutputDto
{
    public function __construct(
        public ?Collection  $payments,
        public Notification $notification
    ) {
    }
}
