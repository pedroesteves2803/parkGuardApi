<?php

use Src\Administration\Application\Employee\Dtos\LoginEmployeeInputDto;

it('can create an instance of LoginEmployeeInputDto with a valid', function () {
    $email = 'teste@teste.com';
    $password = '12345678';

    $inputDto = new LoginEmployeeInputDto(
        $email,
        $password
    );

    expect($inputDto)->toBeInstanceOf(LoginEmployeeInputDto::class);
    expect($inputDto->email)->toBe($email);
    expect($inputDto->password)->toBe($password);
});
