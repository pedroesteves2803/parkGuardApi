<?php

namespace Src\Administration\Application\Employee\Dtos;

final class UpdateEmployeeInputDto
{
    public function __construct(
        readonly string $id,
        readonly string $name,
        readonly string $email,
        readonly string $password,
        readonly int $type,
    ) {
    }
}
