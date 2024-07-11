<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\Services\ILoginEmployeeService;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\Name;
use Src\Administration\Domain\ValueObjects\Password;
use Src\Administration\Domain\ValueObjects\Type;

class LoginEmployeeService implements ILoginEmployeeService
{
    public function login(Email $email, Password $password): ?Employee
    {
        $credentials = ['email' => $email->value(), 'password' => $password->value()];

        if ($token = JWTAuth::attempt($credentials)) {
            $employeeModel = Auth::user();

            $employee = new Employee(
                $employeeModel->id,
                new Name($employeeModel->name),
                new Email($employeeModel->email),
                new Password($employeeModel->password),
                new Type($employeeModel->type),
                $token
            );

            return $employee;
        }

        return null;
    }

    public function logout(string $token): void
    {
        Auth::logout();
    }
}
