<?php

namespace Src\Vehicles\Application\Vehicle\Dtos;

use Illuminate\Support\Collection;
use Src\Shared\Utils\Notification;

final class AddPendingOutputDto
{
    public function __construct(
        readonly ?Collection $pendings,
        readonly Notification $notification
    ) {
    }
}
