<?php

namespace Src\Payments\Application\Payment\Dtos;

use Illuminate\Support\Collection;
use Src\Shared\Utils\Notification;

final class GetAllPaymentsOutputDto
{
    public function __construct(
        readonly ?Collection $payments,
        readonly Notification $notification
    ) {
    }
}
