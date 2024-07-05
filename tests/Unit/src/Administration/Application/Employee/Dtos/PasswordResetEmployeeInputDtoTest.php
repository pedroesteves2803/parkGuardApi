<?php

use Src\Administration\Application\Employee\Dtos\PasswordResetEmployeeInputDto;

it('can create an instance of PasswordResetEmployeeInputDto with a valid ID', function () {
    $email = '1';
    $password = '1';
    $token = 'token';

    $inputDto = new PasswordResetEmployeeInputDto($email, $password, $token);

    expect($inputDto)->toBeInstanceOf(PasswordResetEmployeeInputDto::class);
    expect($inputDto->email)->toBe($email);
    expect($inputDto->password)->toBe($password);
    expect($inputDto->token)->toBe($token);
});
