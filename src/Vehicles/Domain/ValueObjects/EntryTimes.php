<?php

namespace Src\Vehicles\Domain\ValueObjects;

use Src\Shared\Domain\ValueObjects\ValueObject;
use DateTime;

final class EntryTimes extends ValueObject
{
    public function __construct(
        private DateTime $value
    ) {
    }

    public function value(): DateTime
    {
        return $this->value;
    }
}
