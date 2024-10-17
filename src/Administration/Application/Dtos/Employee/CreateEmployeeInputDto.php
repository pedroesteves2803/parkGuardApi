<?php

namespace Src\Administration\Application\Dtos\Employee;

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
