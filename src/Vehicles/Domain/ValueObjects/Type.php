<?php

namespace Src\Vehicles\Domain\ValueObjects;

use Src\Shared\Domain\ValueObjects\ValueObject;

final class Type extends ValueObject
{
    public function __construct(
        private readonly string $value
    ) {
        $this->validate();
    }

    public function validate(): void
    {
        if (empty($this->value)) {
            throw new \RuntimeException('Tipo não pode estar vazio.');
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
