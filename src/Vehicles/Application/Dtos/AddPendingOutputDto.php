<?php

namespace Src\Vehicles\Application\Dtos;

use Illuminate\Support\Collection;
use Src\Shared\Utils\Notification;

final readonly class AddPendingOutputDto
{
    public function __construct(
        public ?Collection  $pendings,
        public Notification $notification
    ) {
    }
}
