<?php

namespace Src\Administration\Application\Employee\Dtos;

use Src\Administration\Domain\Entities\Employee;
use Src\Shared\Utils\Notification;

final readonly class PasswordResetEmployeeOutputDto
{
    public function __construct(
        public ?Employee    $employee,
        public Notification $notification
    ) {}
}
