<?php

use Src\Payments\Application\Dtos\FinalizePaymentInputDto;

it('can create an instance of FinalizePaymentInputDto with valid data', function () {
    $id = 1;

    $inputDto = new FinalizePaymentInputDto(
        $id,
    );

    expect($inputDto)->toBeInstanceOf(FinalizePaymentInputDto::class);
    expect($inputDto->id)->toBe($id);
});
