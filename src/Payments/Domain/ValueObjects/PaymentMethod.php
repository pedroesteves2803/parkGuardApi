<?php

namespace Src\Payments\Domain\ValueObjects;

use Src\Shared\Domain\ValueObjects\ValueObject;

final class PaymentMethod extends ValueObject
{
    public function __construct(
        private readonly int $value
    ) {
        $this->validate();
    }

    public function validate(): void
    {
        if ($this->value !== 1 && $this->value !== 2 && $this->value !== 3) {
            throw new \RuntimeException('Tipo deve ser 1, 2 ou 3.');
        }
    }

    public function value(): int
    {
        return $this->value;
    }
}
