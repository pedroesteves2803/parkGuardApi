<?php

namespace Src\Administration\Application\Employee\Dtos;

final readonly class GeneratePasswordResetTokenEmployeeInputDto
{
    public function __construct(
        public ?string $email,
    ) {
    }
}
