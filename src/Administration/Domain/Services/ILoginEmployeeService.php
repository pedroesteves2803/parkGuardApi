<?php

namespace Src\Administration\Domain\Services;

use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\Password;

interface ILoginEmployeeService
{
    public function login(Email $email, Password $password): ?string;

    public function logout(string $token): void;
}
