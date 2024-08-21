<?php

namespace Src\Administration\Application\Employee\Dtos;

final readonly class LoginEmployeeInputDto
{
    public function __construct(
        public string $email,
        public string $password,
    ) {
    }
}
