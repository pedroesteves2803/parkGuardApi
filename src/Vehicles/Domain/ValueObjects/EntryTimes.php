<?php

namespace Src\Vehicles\Domain\ValueObjects;

use Src\Shared\Domain\ValueObjects\ValueObject;

class EntryTimes extends ValueObject
{
    public function __construct(
        private readonly \DateTime $value
    ) {
    }

    public function value(): \DateTime
    {
        return $this->value;
    }
}
