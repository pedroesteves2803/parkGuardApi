<?php

namespace Src\Administration\Application\Employee\Dtos;

final readonly class CreateEmployeeInputDto
{
    public function __construct(
        public ?string $name,
        public ?string $email,
        public ?string $password,
        public ?int    $type,
    ) {
    }
}
