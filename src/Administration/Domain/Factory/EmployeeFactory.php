<?php

namespace Src\Administration\Domain\Factory;

use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\Name;
use Src\Administration\Domain\ValueObjects\Password;
use Src\Administration\Domain\ValueObjects\Type;

class EmployeeFactory
{
    public function create(
        ?int $id,
        string $name,
        string $email,
        string $password,
        string $type,
        ?string $token
    ): Employee
    {
        return new Employee(
            $id,
            New Name($name),
            new Email($email),
            new Password($password),
            new Type($type),
            $token
        );
    }
}
