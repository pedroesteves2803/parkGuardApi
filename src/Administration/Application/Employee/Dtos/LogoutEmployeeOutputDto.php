<?php

namespace Src\Administration\Application\Employee\Dtos;

use Src\Shared\Utils\Notification;

final readonly class LogoutEmployeeOutputDto
{
    public function __construct(
        public ?string      $token,
        public Notification $notification
    ) {
    }
}
