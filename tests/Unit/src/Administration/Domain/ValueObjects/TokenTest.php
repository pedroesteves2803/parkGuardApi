<?php

use Src\Administration\Domain\ValueObjects\Token;

test('validates instance token', function () {
    $token = new Token('token');
    expect($token)->toBeInstanceOf(Token::class);
});

it('validates a valid token', function () {
    $token = new Token('token');
    expect($token->value())->toBe('token');
});

test('throws an exception when the token has less than 5 characters', function () {
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('Token precisa ter 5 caracteres!');
    new Token('Toke');
});
