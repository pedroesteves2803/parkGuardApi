<?php

use Src\Administration\Application\Employee\Dtos\PasswordResetEmployeeInputDto;

it('can create an instance of PasswordResetEmployeeInputDto with a valid ID', function () {
    $password = '1';
    $token = 'token';

    $inputDto = new PasswordResetEmployeeInputDto($password, $token);

    expect($inputDto)->toBeInstanceOf(PasswordResetEmployeeInputDto::class);
    expect($inputDto->password)->toBe($password);
    expect($inputDto->token)->toBe($token);
});
