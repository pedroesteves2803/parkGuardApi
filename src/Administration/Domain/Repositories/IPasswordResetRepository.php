<?php

namespace Src\Administration\Domain\Repositories;

use Src\Administration\Domain\Entities\PasswordResetToken;
use Src\Administration\Domain\ValueObjects\Email;

interface IPasswordResetRepository
{
    public function getById(int $id): ?PasswordResetToken;

    public function getByEmail(Email $email): ?PasswordResetToken;

    public function create(PasswordResetToken $passwordResetToken): PasswordResetToken;

    public function delete(int $id): void;
}
