<?php

namespace Src\Administration\Application\Employee\Dtos;

use Illuminate\Support\Collection;
use Src\Shared\Utils\Notification;

final class GetAllEmployeesOutputDto
{
    public function __construct(
        readonly ?Collection $employee,
        readonly Notification $notification
    ) {
    }
}
