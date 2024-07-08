<?php

use Src\Administration\Application\Employee\Dtos\VerifyTokenPasswordResetInputDto;

it('can update an instance of VerifyTokenPasswordResetInputDto with valid data', function () {
    $token = 'token';

    $inputDto = new VerifyTokenPasswordResetInputDto($token);

    expect($inputDto)->toBeInstanceOf(VerifyTokenPasswordResetInputDto::class);
    expect($inputDto->token)->toBe($token);
});
