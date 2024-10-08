<?php

namespace Src\Payments\Domain\ValueObjects;

use Src\Shared\Domain\ValueObjects\ValueObject;

final class Value extends ValueObject
{
    private const MIN_VALUE = 0;

    public function __construct(
        private readonly int $value
    ) {
        $this->validate();
    }

    public function validate(): void
    {
        if ($this->value < self::MIN_VALUE) {
            throw new \OutOfRangeException('Valor deve ser pelo menos '.self::MIN_VALUE.'.');
        }
    }

    public function value(): int
    {
        return $this->value;
    }

    public function valueInReais(): float
    {
        return $this->value / 100;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
