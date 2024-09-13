<?php

namespace Src\Administration\Application\Dtos;

final readonly class VerifyTokenPasswordResetInputDto
{
    public function __construct(
        public ?string $token,
    ) {}
}
