<?php

namespace Src\Administration\Application\Dtos;

final readonly class GeneratePasswordResetTokenEmployeeInputDto
{
    public function __construct(
        public ?string $email,
    ) {
    }
}
