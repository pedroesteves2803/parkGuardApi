<?php

namespace App\Repositories\Administration;

use App\Models\Employee as ModelsEmployee;
use App\Models\PasswordResetToken as ModelsPasswordResetToken;
use Illuminate\Support\Collection;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\Entities\PasswordResetToken;
use Src\Administration\Domain\Factory\EmployeeFactory;
use Src\Administration\Domain\Repositories\IEmployeeRepository;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\Token;

final readonly class EloquentEmployeeRepository implements IEmployeeRepository
{

    public function __construct(
        private EmployeeFactory $employeeFactory
    )
    {}

    public function getAll(): ?Collection
    {
        return ModelsEmployee::orderBy('id', 'desc')->get()->map(function ($employee) {
            return $this->employeeFactory->create(
                $employee->id,
                $employee->name,
                $employee->email,
                $employee->password,
                $employee->type,
                null
            );
        });
    }

    public function getById(int $id): ?Employee
    {
        $employee = ModelsEmployee::find($id);

        if (is_null($employee)) {
            return null;
        }

        return $this->employeeFactory->create(
            $employee->id,
            $employee->name,
            $employee->email,
            $employee->password,
            $employee->type,
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

        return $this->employeeFactory->create(
            $modelsEmployee->id,
            $modelsEmployee->name,
            $modelsEmployee->email,
            $modelsEmployee->password,
            $modelsEmployee->type,
            null
        );
    }

    public function update(Employee $employee): ?Employee
    {
        $modelsEmployee = ModelsEmployee::find($employee->id());

        if (is_null($modelsEmployee)) {
            return null;
        }

        $modelsEmployee->name = $employee->name()->value();
        $modelsEmployee->email = $employee->email()->value();
        $modelsEmployee->type = $employee->type()->value();
        $modelsEmployee->update();

        return $this->employeeFactory->create(
            $modelsEmployee->id,
            $modelsEmployee->name,
            $modelsEmployee->email,
            $modelsEmployee->password,
            $modelsEmployee->type,
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

        return $this->employeeFactory->create(
            $modelsEmployee->id,
            $modelsEmployee->name,
            $modelsEmployee->email,
            $modelsEmployee->password,
            $modelsEmployee->type,
            null
        );
    }

    public function updatePassword(PasswordResetToken $passwordResetToken, Employee $employee, Token $token): ?Employee
    {
        $modelsPasswordResetToken = ModelsPasswordResetToken::where([
            'token' => $token->value(),
            'email' => $passwordResetToken->email()->value(),
        ])->first();

        if (! $modelsPasswordResetToken) {
            return null;
        }

        $modelsEmployee = ModelsEmployee::where('email', $passwordResetToken->email()->value())->first();
        $modelsEmployee->password = bcrypt($employee->password()->value());
        $modelsEmployee->update();

        $modelsPasswordResetToken->delete();

        return $this->employeeFactory->create(
            $modelsEmployee->id,
            $modelsEmployee->name,
            $modelsEmployee->email,
            $modelsEmployee->password,
            $modelsEmployee->type,
            null
        );
    }
}
