<?php

namespace Src\Administration\Domain\ValueObjects;

use Src\Shared\Domain\ValueObjects\ValueObject;

class AdditionalHourPrice extends ValueObject
{
    public function __construct(
        private readonly float $additionalHourPrice
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        if($this->additionalHourPrice <=0) {
            throw new \RuntimeException('Preço por hora adicional não pode ser menor que 0.');
        }
    }

    public function value(): float
    {
        return $this->additionalHourPrice;
    }
}
