<?php

namespace Src\Payments\Domain\ValueObjects;

use Src\Shared\Domain\ValueObjects\ValueObject;

final class PaymentMethod extends ValueObject
{
    public function __construct(
        private int $value
    ) {
        $this->validate();
    }

    public function validate()
    {
        if ($this->value !== 1 && $this->value !== 2 && $this->value !== 3) {
            throw new \Exception('Tipo deve ser 1, 2 ou 3.');
        }
    }

    public function value(): int
    {
        return $this->value;
    }
}
