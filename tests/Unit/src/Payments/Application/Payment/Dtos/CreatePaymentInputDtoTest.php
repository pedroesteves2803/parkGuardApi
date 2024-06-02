<?php

use Src\Payments\Application\Payment\Dtos\CreatePaymentInputDto;

it('can create an instance of CreatePaymentInputDto with valid data', function () {
    $dateTime = now();
    $paymentMethod = 1;
    $vehicle_id = 1;

    $inputDto = new CreatePaymentInputDto(
        $dateTime,
        $paymentMethod,
        $vehicle_id,
    );

    expect($inputDto)->toBeInstanceOf(CreatePaymentInputDto::class);
    expect($inputDto->dateTime)->toBe($dateTime);
    expect($inputDto->paymentMethod)->toBe($paymentMethod);
    expect($inputDto->vehicle_id)->toBe($vehicle_id);
});
