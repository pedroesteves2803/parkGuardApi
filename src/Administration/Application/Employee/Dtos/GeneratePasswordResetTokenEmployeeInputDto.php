<?php

namespace Src\Administration\Application\Employee\Dtos;

final class GeneratePasswordResetTokenEmployeeInputDto
{
    public function __construct(
        readonly ?string $email,
    ) {
    }
}
