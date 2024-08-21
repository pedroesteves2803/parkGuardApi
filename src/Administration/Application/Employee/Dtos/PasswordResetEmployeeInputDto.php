<?php

namespace Src\Administration\Application\Employee\Dtos;

final readonly class PasswordResetEmployeeInputDto
{
    public function __construct(
        public ?string $password,
        public ?string $token,
    ) {}
}
