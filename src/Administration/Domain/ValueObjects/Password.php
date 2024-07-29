<?php

namespace Src\Administration\Domain\ValueObjects;

use Src\Shared\Domain\ValueObjects\ValueObject;

final class Password extends ValueObject
{
    public function __construct(
        private string $value,
        private bool $isHashed = false
    ) {
        $this->isHashed = $isHashed;
        $this->validate();
    }

    public function validate()
    {
        if (! $this->isHashed) {
            if (empty($this->value)) {
                throw new \Exception('Senha não pode estar vazio.');
            }

            if (strlen($this->value) < 8) {
                throw new \Exception('Senha deve ter pelo menos 8 caracteres.');
            }

            if (! preg_match('/[A-Z]/', $this->value)) {
                throw new \Exception('Senha deve conter pelo menos uma letra maiúscula.');
            }

            if (! preg_match('/[a-z]/', $this->value)) {
                throw new \Exception('Senha deve conter pelo menos uma letra minúscula.');
            }

            if (! preg_match('/[0-9]/', $this->value)) {
                throw new \Exception('Senha deve conter pelo menos um número.');
            }

            if (! preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $this->value)) {
                throw new \Exception('Senha deve conter pelo menos um carácter especial.');
            }
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function isHashed(): bool
    {
        return $this->isHashed;
    }
}
