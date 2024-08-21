<?php

namespace Src\Administration\Application\Employee\Dtos;

final readonly class VerifyTokenPasswordResetInputDto
{
    public function __construct(
        public ?string $token,
    ) {}
}
