<?php

namespace Src\Administration\Application\Employee\Dtos;

use Src\Administration\Domain\Entities\Employee;
use Src\Shared\Utils\Notification;

final class DeleteEmployeeByIdInputDto
{
    public function __construct(
        readonly ?string $id,
    ) {
    }
}
