<?php

namespace Src\Payments\Domain\ValueObjects;

use Src\Shared\Domain\ValueObjects\ValueObject;

final class RegistrationTime extends ValueObject
{
    public function __construct(
        private readonly ?\DateTime $value
    ) {
    }

    public function value(): ?\DateTime
    {
        return $this->value;
    }
}
