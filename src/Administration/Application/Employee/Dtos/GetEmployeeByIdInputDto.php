<?php

namespace Src\Administration\Application\Employee\Dtos;

final class GetEmployeeByIdInputDto
{
    public function __construct(
        readonly ?string $id,
    ) {
    }
}
