<?php

namespace Src\Shared\Domain\ValueObjects;

final class Email extends ValueObject
{
    public function __construct(
        private string $email
    ) {
        $this->validate();
    }

    public function validate()
    {
        if (empty($this->email)) {
            throw new \Exception('Email cannot be empty.');
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Email not valid.');
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
