<?php

namespace Src\Administration\Application\Dtos;

final readonly class UpdateEmployeeInputDto
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
        public int    $type,
    ) {}
}
