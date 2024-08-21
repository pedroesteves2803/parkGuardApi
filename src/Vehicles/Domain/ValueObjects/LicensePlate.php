<?php

namespace Src\Vehicles\Domain\ValueObjects;

use Src\Shared\Domain\ValueObjects\ValueObject;

final class LicensePlate extends ValueObject
{
    protected string $brazilianStandard = '/^[A-Z]{3}\d{4}$/';

    protected string $mercosurStandard = '/^[A-Z]{3}\d[A-Z]\d{2}$/';

    public function __construct(
        private string $value
    ) {
        $this->value = preg_replace('/[^\p{L}\p{N}\s]/u', '', $value);
        $this->validate();
    }

    public function validate(): void
    {
        if (empty($this->value)) {
            throw new \RuntimeException('Placa não pode estar vazia.');
        }

        if (! preg_match($this->brazilianStandard, $this->value) && ! preg_match($this->mercosurStandard, $this->value)) {
            throw new \RuntimeException('Placa deve ser válida.');
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
