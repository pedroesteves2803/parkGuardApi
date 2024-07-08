<?php

namespace Src\Administration\Application\Employee\Dtos;

final class VerifyTokenPasswordResetInputDto
{
    public function __construct(
        readonly ?string $token,
    ) {}
}
