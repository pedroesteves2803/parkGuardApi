<?php

namespace Src\Shared\Domain\ValueObjects;

use DateTime;

final class DepartureTimes extends ValueObject
{
    public function __construct(
        private DateTime $value
    ) {
        $this->validate();
    }

    public function validate()
    {
        if (empty($this->value)) {
            throw new \Exception('Departure times cannot be empty.');
        }
    }

    public function value(): DateTime
    {
        return $this->value;
    }
}
