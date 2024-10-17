<?php

namespace Src\Administration\Application\Dtos\Employee;

final readonly class VerifyTokenPasswordResetInputDto
{
    public function __construct(
        public ?string $token,
    ) {}
}
