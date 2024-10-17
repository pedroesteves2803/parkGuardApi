<?php

namespace Src\Administration\Application\Dtos\Employee;

final readonly class PasswordResetEmployeeInputDto
{
    public function __construct(
        public ?string $password,
        public ?string $token,
    ) {}
}
