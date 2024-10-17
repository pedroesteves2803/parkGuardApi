<?php

namespace Src\Administration\Application\Dtos\Employee;

final readonly class LoginEmployeeInputDto
{
    public function __construct(
        public string $email,
        public string $password,
    ) {
    }
}
