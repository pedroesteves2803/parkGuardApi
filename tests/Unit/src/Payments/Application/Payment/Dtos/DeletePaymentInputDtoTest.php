<?php

use Src\Payments\Application\Dtos\DeletePaymentInputDto;

it('can create an instance of DeletePaymentInputDto with valid data', function () {
    $id = 1;

    $inputDto = new DeletePaymentInputDto(
        $id,
    );

    expect($inputDto)->toBeInstanceOf(DeletePaymentInputDto::class);
    expect($inputDto->id)->toBe($id);
});
