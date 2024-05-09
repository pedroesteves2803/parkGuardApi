<?php

namespace Src\Administration\Domain\ValueObjects;

use Src\Shared\Domain\ValueObjects\ValueObject;

final class Password extends ValueObject
{
    public function __construct(
        private string $value,
        private bool $isHashed = false
    ) {
        $this->isHashed = $isHashed;
        $this->validate();
    }

    public function validate()
    {
        if (!$this->isHashed) {
            if (empty($this->value)) {
                throw new \Exception('Password cannot be empty.');
            }

            if (strlen($this->value) < 8) {
                throw new \Exception('The password must be at least 8 characters long.');
            }

            if (!preg_match('/[A-Z]/', $this->value)) {
                throw new \Exception('The password must contain at least one capital letter.');
            }

            if (!preg_match('/[a-z]/', $this->value)) {
                throw new \Exception('The password must contain at least one lowercase letter.');
            }

            if (!preg_match('/[0-9]/', $this->value)) {
                throw new \Exception('The password must contain at least one number.');
            }

            if (!preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $this->value)) {
                throw new \Exception('The password must contain at least one special character.');
            }
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function isHashed(): bool
    {
        return $this->isHashed;
    }
}
