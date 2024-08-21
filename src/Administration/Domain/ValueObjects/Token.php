<?php

namespace Src\Administration\Domain\ValueObjects;

use Src\Shared\Domain\ValueObjects\ValueObject;

final class Token extends ValueObject
{
    public function __construct(
        private readonly string $value
    ) {
        $this->validate();
    }

    public function validate(): void
    {
        if (strlen($this->value) !== 5) {
            throw new \RuntimeException('Token precisa ter 5 caracteres!');
        }
    }

    public function value(): string
    {
        return $this->value;
    }
}
