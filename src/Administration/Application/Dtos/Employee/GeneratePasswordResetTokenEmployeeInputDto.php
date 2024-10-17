<?php

namespace Src\Administration\Application\Dtos\Employee;

final readonly class GeneratePasswordResetTokenEmployeeInputDto
{
    public function __construct(
        public ?string $email,
    ) {
    }
}
