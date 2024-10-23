<?php

namespace Src\Administration\Application\Dtos\Employee;

use Src\Administration\Domain\Entities\Employee;
use Src\Shared\Utils\Notification;

final readonly class GetEmployeeByIdOutputDto
{
    public function __construct(
        public ?Employee    $employee,
        public Notification $notification
    ) {
    }
}