<?php

namespace Src\Administration\Infrastructure;

use App\Models\Employee as ModelsEmployee;
use Illuminate\Support\Collection;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\Repositories\IEmployeeRepository;
use Src\Shared\Domain\ValueObjects\Email;
use Src\Shared\Domain\ValueObjects\Name;
use Src\Shared\Domain\ValueObjects\Password;
use Src\Shared\Domain\ValueObjects\Type;

final class EloquentEmployeeRepository implements IEmployeeRepository
{
    public function getAll(): ?Collection
    {
        $employees = ModelsEmployee::all();

        $employees = $employees->map(function ($employee) {
            return new Employee(
                $employee->id,
                new Name($employee->name),
                new Email($employee->email),
                new Password($employee->password),
                new Type($employee->type),
            );
        });

        return $employees;
    }

    public function getById(int $id): ?Employee
    {
        $employee = ModelsEmployee::find($id);

        if (is_null($employee)) {
            return null;
        }

        return new Employee(
            $employee->id,
            new Name($employee->name),
            new Email($employee->email),
            new Password($employee->password),
            new Type($employee->type)
        );
    }

    public function create(Employee $employee): Employee
    {
        $modelsEmployee = new ModelsEmployee();
        $modelsEmployee->name = $employee->name->value();
        $modelsEmployee->email = $employee->email->value();
        $modelsEmployee->password = bcrypt($employee->password->value());
        $modelsEmployee->type = $employee->type->value();
        $modelsEmployee->save();

        return new Employee(
            $modelsEmployee->id,
            new Name($modelsEmployee->name),
            new Email($modelsEmployee->email),
            new Password($modelsEmployee->password, true),
            new Type($modelsEmployee->type)
        );
    }

    public function update(Employee $employee): Employee
    {
        $modelsEmployee = ModelsEmployee::find($employee->id);

        if (is_null($modelsEmployee)) {
            return null;
        }

        $modelsEmployee->name = $employee->name->value();
        $modelsEmployee->email = $employee->email->value();
        $modelsEmployee->type = $employee->type->value();
        $modelsEmployee->update();

        return new Employee(
            $modelsEmployee->id,
            new Name($modelsEmployee->name),
            new Email($modelsEmployee->email),
            new Password($modelsEmployee->password),
            new Type($modelsEmployee->type)
        );
    }

    public function delete(int $id): void
    {
        $modelsEmployee = ModelsEmployee::find($id);
        $modelsEmployee->delete();
    }

    public function existByEmail(Email $email): bool
    {
        return ModelsEmployee::where('email', $email->value())->exists();
    }
}
