<?php

namespace Src\Administration\Application\Employee\Dtos;

final readonly class LogoutEmployeeInputDto
{
    public function __construct(
        public string $token,
    ) {
    }
}
