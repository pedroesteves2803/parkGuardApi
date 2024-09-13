<?php

namespace Src\Administration\Application\Dtos;

final readonly class LogoutEmployeeInputDto
{
    public function __construct(
        public string $token,
    ) {
    }
}
