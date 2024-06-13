<?php

namespace Src\Administration\Application\Employee\Dtos;

use Src\Shared\Utils\Notification;

final class LogoutEmployeeOutputDto
{
    public function __construct(
        readonly ?string $token,
        readonly Notification $notification
    ) {
    }
}
