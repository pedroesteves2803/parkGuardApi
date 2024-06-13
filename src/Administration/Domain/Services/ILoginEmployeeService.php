<?php

namespace Src\Administration\Domain\Services;

use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\Password;

interface ILoginEmployeeService
{
    public function login(Email $email, Password $password): ?Employee;

    public function logout(string $token): void;
}
