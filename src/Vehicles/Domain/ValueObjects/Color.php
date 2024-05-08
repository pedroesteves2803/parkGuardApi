<?php

namespace Src\Shared\Domain\ValueObjects;

final class Color extends ValueObject
{
    public function __construct(
        private string $value
    ) {
        $this->validate();
    }

    public function validate()
    {
        if (empty($this->value)) {
            throw new \Exception('Codel cannot be empty.');
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
