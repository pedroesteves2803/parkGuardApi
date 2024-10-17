<?php

namespace Src\Administration\Application\Dtos\Employee;

use Src\Shared\Utils\Notification;

final readonly class LogoutEmployeeOutputDto
{
    public function __construct(
        public ?string      $token,
        public Notification $notification
    ) {
    }
}
