<?php

namespace Src\Administration\Domain\ValueObjects;

use Src\Shared\Domain\ValueObjects\ValueObject;

final class Description extends ValueObject
{
    public function __construct(
        private string $description
    ) {
        $this->validate();
    }

    public function validate()
    {
        if (empty($this->description)) {
            throw new \Exception('Description cannot be empty.');
        }

        if (strlen($this->description) < 50) {
            throw new \Exception('Description must be at least 50 characters long.');
        }
    }

    public function value(): string
    {
        return $this->description;
    }

    public function __toString(): string
    {
        return $this->description;
    }
}
