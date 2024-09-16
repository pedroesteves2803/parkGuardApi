<?php

namespace Src\Administration\Application\Dtos;

use Src\Administration\Domain\Entities\Employee;
use Src\Shared\Utils\Notification;

final readonly class LoginEmployeeOutputDto
{
    public function __construct(
        public ?Employee    $employee,
        public Notification $notification
    ) {
    }
}