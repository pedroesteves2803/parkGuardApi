<?php

namespace Src\Administration\Domain\Entities;

use Illuminate\Support\Str;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\ExpirationTime;
use Src\Administration\Domain\ValueObjects\Token;
use Src\Shared\Domain\Entities\Entity;
use Src\Shared\Domain\Entities\IAggregator;

class PasswordResetToken extends Entity implements IAggregator
{
    public function __construct(
        readonly private ?Email $email,
        private ?Token $token,
        readonly private ?ExpirationTime $expirationTime,
    ) {
        $this->token = $this->token ?? $this->generateToken();
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function token(): ?Token
    {
        return $this->token;
    }

    public function expirationTime(): ?ExpirationTime
    {
        return $this->expirationTime;
    }

    private function generateToken(): Token
    {
        return new Token(strtoupper(Str::random(5)));
    }
}
