<?php

namespace Src\Administration\Application\Dtos\Employee;

final readonly class DeleteEmployeeByIdInputDto
{
    public function __construct(
        public ?string $id,
    ) {
    }
}
