<?php

namespace Src\Administration\Application\Dtos\Employee;

final readonly class UpdateEmployeeInputDto
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
        public int    $type,
    ) {}
}
