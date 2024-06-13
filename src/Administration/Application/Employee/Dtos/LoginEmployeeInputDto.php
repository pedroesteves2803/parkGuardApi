<?php

namespace Src\Administration\Application\Employee\Dtos;

final class LoginEmployeeInputDto
{
    public function __construct(
        readonly string $email,
        readonly string $password,
    ) {
    }
}
