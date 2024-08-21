<?php

namespace Src\Administration\Application\Employee\Dtos;

final readonly class DeleteEmployeeByIdInputDto
{
    public function __construct(
        public ?string $id,
    ) {
    }
}
