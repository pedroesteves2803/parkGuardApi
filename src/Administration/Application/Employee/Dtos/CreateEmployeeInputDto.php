<?php

namespace Src\Administration\Application\Employee\Dtos;

final class GeneratePasswordResetTokenEmployeeInputDto
{
    public function __construct(
        readonly ?string $name,
        readonly ?string $email,
        readonly ?string $password,
        readonly ?int $type,
    ) {
    }
}
