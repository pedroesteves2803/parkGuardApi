<?php

namespace Src\Administration\Application\Employee\Dtos;

final readonly class GetEmployeeByIdInputDto
{
    public function __construct(
        public ?string $id,
    ) {
    }
}
