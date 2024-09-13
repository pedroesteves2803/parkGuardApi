<?php

namespace Src\Administration\Application\Dtos;

final readonly class LoginEmployeeInputDto
{
    public function __construct(
        public string $email,
        public string $password,
    ) {
    }
}
