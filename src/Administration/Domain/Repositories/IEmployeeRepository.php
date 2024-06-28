<?php

namespace Src\Administration\Domain\Repositories;

use Illuminate\Support\Collection;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\ValueObjects\Email;

interface IEmployeeRepository
{
    public function getAll(): ?Collection;

    public function getById(int $id): ?Employee;

    public function create(Employee $employee): Employee;

    public function update(Employee $employee): Employee;

    public function delete(int $id): void;

    public function existByEmail(Email $email): bool;

    public function getByEmail(Email $email): Employee;
}
