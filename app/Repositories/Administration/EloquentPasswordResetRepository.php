<?php

namespace App\Repositories\Administration;

use App\Models\PasswordResetToken as ModelsPasswordResetToken;
use Src\Administration\Domain\Entities\PasswordResetToken;
use Src\Administration\Domain\Repositories\IPasswordResetRepository;
use Src\Administration\Domain\ValueObjects\Email;

final class EloquentPasswordResetRepository implements IPasswordResetRepository
{
    public function getById(int $id): ?PasswordResetToken
    {
        $modelsPasswordResetToken =  ModelsPasswordResetToken::find($id);

        if (is_null($modelsPasswordResetToken)) {
            return null;
        }

        return new PasswordResetToken(
            new Email($modelsPasswordResetToken->email),
            $modelsPasswordResetToken->token
        );
    }

    public function create(PasswordResetToken $passwordResetToken): PasswordResetToken
    {
        $modelsPasswordResetToken = new ModelsPasswordResetToken();

        $modelsPasswordResetToken->email = $passwordResetToken->token()->value();
        $modelsPasswordResetToken->token = $passwordResetToken->email()->value();

    }

    public function delete(int $id): void {

    }
}
