<?php

namespace Src\Administration\Application\Employee\Dtos;

final class LogoutEmployeeInputDto
{
    public function __construct(
        readonly string $token,
    ) {
    }
}
