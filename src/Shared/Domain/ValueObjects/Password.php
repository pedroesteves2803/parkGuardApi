<?php

namespace Src\Shared\Domain\ValueObjects;

final class Password extends ValueObject
{
    public function __construct(
        private string $password
    ) {
        $this->validate();
    }

    public function validate()
    {
        if (empty($this->password)) {
            throw new \Exception('Password cannot be empty.');
        }

        if (strlen($this->password) < 8) {
            throw new \Exception('The password must be at least 8 characters long.');
        }

        if (!preg_match('/[A-Z]/', $this->password)) {
            throw new \Exception('The password must contain at least one capital letter.');
        }

        if (!preg_match('/[a-z]/', $this->password)) {
            throw new \Exception('The password must contain at least one lowercase letter.');
        }

        if (!preg_match('/[0-9]/', $this->password)) {
            throw new \Exception('The password must contain at least one number.');
        }

        if (!preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $this->password)) {
            throw new \Exception('The password must contain at least one special character.');
        }
    }

    public function value(): string
    {
        return $this->password;
    }
}
