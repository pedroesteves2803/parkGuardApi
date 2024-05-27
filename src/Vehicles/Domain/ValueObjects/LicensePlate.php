<?php

namespace Src\Vehicles\Domain\ValueObjects;

use Src\Shared\Domain\ValueObjects\ValueObject;

final class LicensePlate extends ValueObject
{
    protected $brazilianStandard = '/^[A-Z]{3}\d{4}$/';

    protected $mercosurStandard = '/^[A-Z]{3}\d[A-Z]\d{2}$/';

    public function __construct(
        private string $value
    ) {
        $this->value = preg_replace('/[^\p{L}\p{N}\s]/u', '', $value);
        $this->validate();
    }

    public function validate()
    {
        if (empty($this->value)) {
            throw new \Exception('LicensePlate cannot be empty.');
        }

        if (! preg_match($this->brazilianStandard, $this->value) && ! preg_match($this->mercosurStandard, $this->value)) {
            throw new \Exception('It must be a valid license plate.');
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
