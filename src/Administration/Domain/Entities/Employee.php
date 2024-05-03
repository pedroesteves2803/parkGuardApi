<?php

namespace Src\Administration\Domain\Entities;

use Src\Shared\Domain\Entities\Entity;
use Src\Shared\Domain\Entities\IAggregator;
use Src\Shared\Domain\ValueObjects\Email;
use Src\Shared\Domain\ValueObjects\Name;
use Src\Shared\Domain\ValueObjects\Password;
use Src\Shared\Domain\ValueObjects\Type;

class Employee extends Entity implements IAggregator
{
    public function __construct(
        readonly ?int $id,
        readonly Name $name,
        readonly Email $email,
        readonly Password $password,
        readonly Type $type,
    ) {
    }

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function __toString(): string
    {
        return "Funcionario ID: {$this->id}, Nome: {$this->name->value()}, Email: {$this->email->value()}, Tipo: {$this->type->value()}";
    }
}