<?php

namespace Src\Administration\Domain\Repositories;

use Src\Administration\Domain\Entities\PasswordResetToken;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\Token;

interface IPasswordResetRepository
{
    public function getById(int $id): ?PasswordResetToken;

    public function getByEmail(Email $email): ?PasswordResetToken;

    public function getByToken(Token $token): ?PasswordResetToken;

    public function create(PasswordResetToken $passwordResetToken): PasswordResetToken;

    public function delete(Email $email): void;
}
