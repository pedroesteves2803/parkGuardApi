<?php

namespace Src\Shared\Domain\ValueObjects;

use Src\Shared\Domain\ValueObjects\ValueObject;
use Exception;

final class Type extends ValueObject{

    public function __construct(
        private int $type
    ){
        $this->validate();
    }

    public function validate(){
        if ($this->type !== 1 && $this->type !== 2) {
            throw new Exception('Type must be 1 or 2.');
        }
    }

    public function value(): int{
        return $this->type;
    }
}
