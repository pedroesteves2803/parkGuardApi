<?php

namespace Src\Vehicles\Domain\ValueObjects;

use Src\Shared\Domain\ValueObjects\ValueObject;

final class DepartureTimes extends ValueObject
{
    public function __construct(
        private ?\DateTime $value
    ) {
    }

    public function value(): ?\DateTime
    {
        return $this->value;
    }
}
