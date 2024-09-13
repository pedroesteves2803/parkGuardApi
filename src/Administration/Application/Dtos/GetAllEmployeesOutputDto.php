<?php

namespace Src\Administration\Application\Dtos;

use Illuminate\Support\Collection;
use Src\Shared\Utils\Notification;

final readonly class GetAllEmployeesOutputDto
{
    public function __construct(
        public ?Collection  $employees,
        public Notification $notification
    ) {
    }
}
