<?php

namespace Src\Administration\Domain\ValueObjects;

use Src\Shared\Domain\ValueObjects\ValueObject;

final class Type extends ValueObject
{
    public function __construct(
        private readonly int $value
    ) {
        $this->validate();
    }

    public function validate(): void
    {
        if ($this->value !== 1 && $this->value !== 2) {
            throw new \RuntimeException('Tipo deve ser 1 ou 2.');
        }
    }

    public function value(): int
    {
        return $this->value;
    }
}
