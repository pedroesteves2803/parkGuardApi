<?php

namespace Src\Administration\Domain\ValueObjects;

use Src\Shared\Domain\ValueObjects\ValueObject;

final class Type extends ValueObject
{
    public function __construct(
        private int $type
    ) {
        $this->validate();
    }

    public function validate()
    {
        if (1 !== $this->type && 2 !== $this->type) {
            throw new \Exception('Type must be 1 or 2.');
        }
    }

    public function value(): int
    {
        return $this->type;
    }
}
