<?php

namespace Src\Vehicles\Domain\ValueObjects;

use Src\Shared\Domain\ValueObjects\ValueObject;
use DateTime;

final class EntryTimes extends ValueObject
{
    public function __construct(
        private DateTime $value
    ) {
        $this->validate();
    }

    public function validate()
    {
        if (empty($this->value)) {
            throw new \Exception('Entry Times cannot be empty.');
        }
    }

    public function value(): DateTime
    {
        return $this->value;
    }
}
