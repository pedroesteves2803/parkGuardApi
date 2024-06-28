<?php

namespace Src\Administration\Domain\ValueObjects;

use Src\Shared\Domain\ValueObjects\ValueObject;

final class ExpirationTime extends ValueObject
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
