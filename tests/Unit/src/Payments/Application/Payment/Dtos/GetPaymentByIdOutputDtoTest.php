<?php

use Src\Payments\Application\Payment\Dtos\GetPaymentByIdOutputDto;
use Src\Payments\Domain\Entities\Payment;
use Src\Shared\Utils\Notification;

it('can create an instance of GetPaymentByIdOutputDto with valid data', function () {
    $payment = mock(Payment::class);

    $outputDto = new GetPaymentByIdOutputDto(
        $payment,
        new Notification()
    );

    expect($outputDto)->toBeInstanceOf(GetPaymentByIdOutputDto::class);
    expect($outputDto->payment)->toBe($payment);
    expect($outputDto->notification->getErrors())->toBe([]);
});

it('can create an instance of GetPaymentByIdOutputDto with null employee and error notifications', function () {
    $notification = new Notification();

    $notification->addError([
        'context' => 'test_error',
        'message' => 'test',
    ]);

    $outputDto = new GetPaymentByIdOutputDto(null, $notification);

    expect($outputDto)->toBeInstanceOf(GetPaymentByIdOutputDto::class);
    expect($outputDto->payment)->toBeNull();
    expect($outputDto->notification->getErrors())->toBe([
        [
            'context' => 'test_error',
            'message' => 'test',
        ],
    ]);
});
