<?php

namespace App\Repositories\Administration;

use App\Models\PasswordResetToken as ModelsPasswordResetToken;
use Src\Administration\Domain\Entities\PasswordResetToken;
use Src\Administration\Domain\Repositories\IPasswordResetRepository;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\ExpirationTime;
use Src\Administration\Domain\ValueObjects\Token;

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
            new Token($modelsPasswordResetToken->token),
            new ExpirationTime($modelsPasswordResetToken->expiration_date)
        );
    }

    public function getByEmail(Email $email): ?PasswordResetToken
    {
        $modelsPasswordResetToken =  ModelsPasswordResetToken::where('email', $email)->first();

        if (is_null($modelsPasswordResetToken)) {
            return null;
        }

        return new PasswordResetToken(
            new Email($modelsPasswordResetToken->email),
            new Token($modelsPasswordResetToken->token),
            new ExpirationTime($modelsPasswordResetToken->expiration_date)
        );
    }

    public function getByToken(Token $token): ?PasswordResetToken
    {
        $modelsPasswordResetToken =  ModelsPasswordResetToken::where('token', $token->value())->first();

        if (is_null($modelsPasswordResetToken)) {
            return null;
        }

        return new PasswordResetToken(
            new Email($modelsPasswordResetToken->email),
            new Token($modelsPasswordResetToken->token),
            new ExpirationTime($modelsPasswordResetToken->expiration_date)
        );
    }

    public function create(PasswordResetToken $passwordResetToken): PasswordResetToken
    {
        $modelsPasswordResetToken = new ModelsPasswordResetToken();

        $modelsPasswordResetToken->email = $passwordResetToken->email()->value();
        $modelsPasswordResetToken->token = $passwordResetToken->token()->value();
        $modelsPasswordResetToken->expiration_date = now()->addMinutes(40);
        $modelsPasswordResetToken->save();

        return new PasswordResetToken(
            new Email($modelsPasswordResetToken->email),
            new Token($modelsPasswordResetToken->token),
            new ExpirationTime($modelsPasswordResetToken->expiration_date)
        );
    }

    public function delete(Email $email): void
    {
        $modelsPasswordResetToken =  ModelsPasswordResetToken::where('email', $email)->first();
        $modelsPasswordResetToken->delete();
    }

}
