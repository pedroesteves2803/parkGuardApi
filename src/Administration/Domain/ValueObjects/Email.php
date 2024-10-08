<?php

namespace Src\Administration\Domain\ValueObjects;

use Src\Shared\Domain\ValueObjects\ValueObject;

final class Email extends ValueObject
{
    public function __construct(
        private string $email
    ) {
        $this->email = strtolower($email);
        $this->validate();
    }

    public function validate(): void
    {
        if (empty($this->email)) {
            throw new \RuntimeException('Email não pode estar vazio.');
        }

        if (! filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new \RuntimeException('Email não válido.');
        }
    }

    public function value(): string
    {
        return $this->email;
    }

    public function __toString(): string
    {
        return $this->email;
    }
}
