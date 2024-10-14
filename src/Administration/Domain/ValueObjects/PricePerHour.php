<?php

namespace Src\Administration\Domain\ValueObjects;

use Src\Shared\Domain\ValueObjects\ValueObject;

final class PricePerHour extends ValueObject
{
    public function __construct(
        private readonly float $pricePerHour
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        if($this->pricePerHour <=0) {
            throw new \RuntimeException('Preço por hora não pode ser menor que 0.');
        }
    }

    public function value(): string
    {
        return $this->pricePerHour;
    }
}
