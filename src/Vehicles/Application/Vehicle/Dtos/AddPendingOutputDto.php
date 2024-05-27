<?php

namespace Src\Vehicles\Application\Vehicle\Dtos;

use Src\Shared\Utils\Notification;

final class AddPendingOutputDto
{
    public function __construct(
        readonly Notification $notification
    ) {
    }
}
