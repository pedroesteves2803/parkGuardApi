<?php

namespace Src\Shared\Domain\ValueObjects;

final class Name extends ValueObject
{
    public function __construct(
        private string $name
    ) {
        $this->validate();
    }

    public function validate()
    {
        if (empty($this->name)) {
            throw new \Exception('Name cannot be empty.');
        }
    }

    public function value(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
