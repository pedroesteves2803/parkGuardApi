<?php

namespace Src\Administration\Application\Dtos\Employee;

final readonly class GetEmployeeByIdInputDto
{
    public function __construct(
        public ?string $id,
    ) {
    }
}
