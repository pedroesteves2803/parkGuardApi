<?php

use Src\Payments\Application\Dtos\CreatePaymentInputDto;

it('can create an instance of CreatePaymentInputDto with valid data', function () {
    $dateTime = now();
    $paymentMethod = 1;
    $vehicle_id = 1;

    $inputDto = new CreatePaymentInputDto(
        $dateTime,
        $paymentMethod,
        $vehicle_id,
    );

    expect($inputDto)->toBeInstanceOf(CreatePaymentInputDto::class)
        ->and($inputDto->dateTime)->toBe($dateTime)
        ->and($inputDto->paymentMethod)->toBe($paymentMethod)
        ->and($inputDto->vehicle_id)->toBe($vehicle_id);
});
