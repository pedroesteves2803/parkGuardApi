<?php

namespace Src\Administration\Application\Employee\Dtos;

final class VerifyTokenPasswordResetDto
{
    public function __construct(
        readonly ?string $token,
    ) {}
}
