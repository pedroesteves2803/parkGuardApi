<?php

namespace Src\Administration\Domain\ValueObjects;

use Src\Shared\Domain\ValueObjects\ValueObject;

final class Type extends ValueObject
{
    public function __construct(
        private int $value
    ) {
        $this->validate();
    }

    public function validate()
    {
        if ($this->value !== 1 && $this->value !== 2) {
            throw new \Exception('Tipo deve ser 1 ou 2.');
        }
    }

    public function value(): int
    {
        return $this->value;
    }
}
