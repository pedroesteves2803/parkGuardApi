<?php

namespace Src\Administration\Application\Employee\Dtos;

final class DeleteEmployeeByIdInputDto
{
    public function __construct(
        readonly ?string $id,
    ) {
    }
}
