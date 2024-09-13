<?php

namespace Src\Administration\Application\Dtos;

final readonly class GetEmployeeByIdInputDto
{
    public function __construct(
        public ?string $id,
    ) {
    }
}
