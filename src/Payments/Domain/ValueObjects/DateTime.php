<?php

namespace Src\Payments\Domain\ValueObjects;

use Src\Shared\Domain\ValueObjects\ValueObject;

final class DateTime extends ValueObject
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
