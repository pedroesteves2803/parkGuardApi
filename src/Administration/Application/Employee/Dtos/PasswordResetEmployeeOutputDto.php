<?php

namespace Src\Administration\Application\Employee\Dtos;

use Src\Administration\Domain\Entities\Employee;
use Src\Shared\Utils\Notification;

final class PasswordResetEmployeeOutputDto
{
    public function __construct(
        readonly ?Employee $employee,
        readonly Notification $notification
    ) {}
}
