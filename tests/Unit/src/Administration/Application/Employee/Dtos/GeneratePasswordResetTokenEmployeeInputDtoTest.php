<?php

use Src\Administration\Application\Employee\Dtos\GeneratePasswordResetTokenEmployeeInputDto;

it('can create an instance of GeneratePasswordResetTokenEmployeeInputDto with a valid ID', function () {
    $email = 'email@email.com';

    $inputDto = new GeneratePasswordResetTokenEmployeeInputDto($email);

    expect($inputDto)->toBeInstanceOf(GeneratePasswordResetTokenEmployeeInputDto::class);
    expect($inputDto->email)->toBe($email);
});
