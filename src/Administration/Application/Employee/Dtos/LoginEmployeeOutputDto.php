<?php

namespace Src\Administration\Application\Employee\Dtos;

use Src\Administration\Domain\Entities\Employee;
use Src\Shared\Utils\Notification;

final class LoginEmployeeOutputDto
{
    public function __construct(
        readonly ?string $token,
        readonly Notification $notification
    ) {
    }
}
