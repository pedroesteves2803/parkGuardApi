<?php

namespace Src\Vehicles\Domain\ValueObjects;

use Src\Shared\Domain\ValueObjects\ValueObject;

final class LicensePlate extends ValueObject
{
    public function __construct(
        private string $value
    ) {
        $this->validate();
    }

    public function validate()
    {
        if (empty($this->value)) {
            throw new \Exception('LicensePlate cannot be empty.');
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
