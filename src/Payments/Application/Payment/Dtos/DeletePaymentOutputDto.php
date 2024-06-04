<?php

namespace Src\Payments\Application\Payment\Dtos;

use Src\Payments\Domain\Entities\Payment;
use Src\Shared\Utils\Notification;

final class DeletePaymentOutputDto
{
    public function __construct(
        readonly ?Payment $payment,
        readonly Notification $notification
    ) {
    }
}
