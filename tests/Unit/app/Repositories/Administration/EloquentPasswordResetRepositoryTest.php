<?php

use App\Models\PasswordResetToken as ModelsPasswordResetToken;
use App\Repositories\Administration\EloquentPasswordResetRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Administration\Domain\Entities\PasswordResetToken;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\Token;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('can get password reset token by id', function () {
    $passwordResetToken = ModelsPasswordResetToken::factory()->create();

    $repository = new EloquentPasswordResetRepository();

    $retrievedPasswordResetToken = $repository->getById($passwordResetToken->id);

    expect($retrievedPasswordResetToken)->toBeInstanceOf(PasswordResetToken::class);

    expect($retrievedPasswordResetToken->email()->value())->toBe($passwordResetToken->email);
    expect($retrievedPasswordResetToken->token()->value())->toBe($passwordResetToken->token);
    expect($retrievedPasswordResetToken->expirationTime())->not()->toBeNull();
});

it('can get password reset token by email', function () {
    $passwordResetToken = ModelsPasswordResetToken::factory()->create();

    $repository = new EloquentPasswordResetRepository();

    $retrievedPasswordResetToken = $repository->getByEmail(new Email($passwordResetToken->email));

    expect($retrievedPasswordResetToken)->toBeInstanceOf(PasswordResetToken::class);

    expect($retrievedPasswordResetToken->email()->value())->toBe($passwordResetToken->email);
    expect($retrievedPasswordResetToken->token()->value())->toBe($passwordResetToken->token);
    expect($retrievedPasswordResetToken->expirationTime())->not()->toBeNull();
});

it('can get password reset token by token', function () {
    $passwordResetToken = ModelsPasswordResetToken::factory()->create();

    $repository = new EloquentPasswordResetRepository();

    $retrievedPasswordResetToken = $repository->getByToken(new Token($passwordResetToken->token));

    expect($retrievedPasswordResetToken)->toBeInstanceOf(PasswordResetToken::class);

    expect($retrievedPasswordResetToken->email()->value())->toBe($passwordResetToken->email);
    expect($retrievedPasswordResetToken->token()->value())->toBe($passwordResetToken->token);
    expect($retrievedPasswordResetToken->expirationTime())->not()->toBeNull();
});

it('create a new password reset token', function () {
    $passwordResetTokenData = new PasswordResetToken(
        new Email('email@test.com'),
        new Token('token'),
        null,
    );

    $repository = new EloquentPasswordResetRepository();
    $createdPasswordResetToken = $repository->create($passwordResetTokenData);

    expect($createdPasswordResetToken)->toBeInstanceOf(PasswordResetToken::class);
    expect($createdPasswordResetToken->email()->value())->toBe($passwordResetTokenData->email()->value());
    expect($createdPasswordResetToken->token()->value())->toBe($passwordResetTokenData->token()->value());
    expect($createdPasswordResetToken->expirationTime()->value())->not()->toBeNull();
});

it('delete a password reset token', function () {
    $passwordResetToken = ModelsPasswordResetToken::factory()->create();

    $repository = new EloquentPasswordResetRepository();
    $repository->delete(new Email($passwordResetToken->email));
    $deleteEmployee = $repository->getById(1);

    $this->assertNull($deleteEmployee);
});
