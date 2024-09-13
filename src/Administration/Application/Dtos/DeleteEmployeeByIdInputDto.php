<?php

namespace Src\Administration\Application\Dtos;

final readonly class DeleteEmployeeByIdInputDto
{
    public function __construct(
        public ?string $id,
    ) {
    }
}
