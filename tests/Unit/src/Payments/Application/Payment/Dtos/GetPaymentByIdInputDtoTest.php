<?php

use Src\Payments\Application\Dtos\GetPaymentByIdInputDto;

it('can create an instance of GetPaymentByIdInputDto with valid data', function () {
    $id = 1;

    $inputDto = new GetPaymentByIdInputDto(
        $id,
    );

    expect($inputDto)->toBeInstanceOf(GetPaymentByIdInputDto::class);
    expect($inputDto->id)->toBe($id);
});
