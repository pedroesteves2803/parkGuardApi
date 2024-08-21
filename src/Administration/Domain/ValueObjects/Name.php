<?php

namespace Src\Administration\Domain\ValueObjects;

use Src\Shared\Domain\ValueObjects\ValueObject;

final class Name extends ValueObject
{
    public function __construct(
        private readonly string $name
    ) {
        $this->validate();
    }

    public function validate(): void
    {
        if (empty($this->name)) {
            throw new \RuntimeException('Nome não pode estar vazio.');
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
