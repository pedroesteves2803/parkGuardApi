<?php

namespace Src\Administration\Application\Employee\Dtos;

final class PasswordResetEmployeeInputDto
{
    public function __construct(
        readonly ?string $password,
        readonly ?string $token,
    ) {}
}
