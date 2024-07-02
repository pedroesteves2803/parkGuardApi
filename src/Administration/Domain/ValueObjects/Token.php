<?php

namespace Src\Administration\Domain\ValueObjects;

use Src\Shared\Domain\ValueObjects\ValueObject;

final class Token extends ValueObject
{
    public function __construct(
        private string $value
    ) {
        $this->validate();
    }

    public function validate()
    {
        if (strlen($this->value) !== 5) {
            throw new \Exception('Token precisa ter 5 caracteres!');
        }
    }

    public function value(): string
    {
        return $this->value;
    }
}
