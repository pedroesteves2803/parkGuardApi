<?php

namespace App\Repositories\Administration;

use App\Models\Employee as ModelsEmployee;
use App\Models\PasswordResetToken as ModelsPasswordResetToken;
use Illuminate\Support\Collection;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\Entities\PasswordResetToken;
use Src\Administration\Domain\Repositories\IEmployeeRepository;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\Name;
use Src\Administration\Domain\ValueObjects\Password;
use Src\Administration\Domain\ValueObjects\Token;
use Src\Administration\Domain\ValueObjects\Type;

final class EloquentEmployeeRepository implements IEmployeeRepository
{
    public function getAll(): ?Collection
    {
        $employees = ModelsEmployee::orderBy('id', 'desc')->get();

        $employees = $employees->map(function ($employee) {
            return new Employee(
                $employee->id,
                new Name($employee->name),
                new Email($employee->email),
                new Password($employee->password),
                new Type($employee->type),
                null
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
            new Type($employee->type),
            null
        );
    }

    public function create(Employee $employee): Employee
    {
        $modelsEmployee = new ModelsEmployee();
        $modelsEmployee->name = $employee->name()->value();
        $modelsEmployee->email = $employee->email()->value();
        $modelsEmployee->password = bcrypt($employee->password()->value());
        $modelsEmployee->type = $employee->type()->value();
        $modelsEmployee->save();

        return new Employee(
            $modelsEmployee->id,
            new Name($modelsEmployee->name),
            new Email($modelsEmployee->email),
            new Password($modelsEmployee->password, true),
            new Type($modelsEmployee->type),
            null
        );
    }

    public function update(Employee $employee): Employee
    {
        $modelsEmployee = ModelsEmployee::find($employee->id());

        if (is_null($modelsEmployee)) {
            return null;
        }

        $modelsEmployee->name = $employee->name()->value();
        $modelsEmployee->email = $employee->email()->value();
        $modelsEmployee->type = $employee->type()->value();
        $modelsEmployee->update();

        return new Employee(
            $modelsEmployee->id,
            new Name($modelsEmployee->name),
            new Email($modelsEmployee->email),
            new Password($modelsEmployee->password),
            new Type($modelsEmployee->type),
            null
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

    public function getByEmail(Email $email): ?Employee
    {
        $modelsEmployee = ModelsEmployee::where('email', $email->value())->first();

        if (is_null($modelsEmployee)) {
            return null;
        }

        return new Employee(
            $modelsEmployee->id,
            new Name($modelsEmployee->name),
            new Email($modelsEmployee->email),
            new Password($modelsEmployee->password),
            new Type($modelsEmployee->type),
            null
        );
    }

    public function updatePassword(PasswordResetToken $passwordResetToken, Employee $employee, Token $token): Employee
    {
        $modelsPasswordResetToken = ModelsPasswordResetToken::where([
            'token' => $token->value(),
            'email' => $passwordResetToken->email()->value(),
        ])->first();

        if (! $modelsPasswordResetToken) {
            return null;
        }

        $modelsEmployee = ModelsEmployee::where('email', $passwordResetToken->email()->value())->first();
        $modelsEmployee->password = $employee->password()->value();
        $modelsEmployee->update();

        return new Employee(
            $modelsEmployee->id,
            new Name($modelsEmployee->name),
            new Email($modelsEmployee->email),
            new Password($modelsEmployee->password),
            new Type($modelsEmployee->type),
            null
        );
    }
}
