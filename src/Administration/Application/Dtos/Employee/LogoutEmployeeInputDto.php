<?php

namespace Src\Administration\Application\Dtos\Employee;

final readonly class LogoutEmployeeInputDto
{
    public function __construct(
        public string $token,
    ) {
    }
}
