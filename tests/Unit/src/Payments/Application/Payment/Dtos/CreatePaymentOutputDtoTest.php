<?php

use Src\Payments\Application\Dtos\CreatePaymentOutputDto;
use Src\Payments\Domain\Entities\Payment;
use Src\Shared\Utils\Notification;

it('can create an instance of CreatePaymentOutputDto with a valid employee', function () {
    $notification = new Notification();
    $payment = mock(Payment::class);

    $outputDto = new CreatePaymentOutputDto($payment, $notification);

    expect($outputDto)->toBeInstanceOf(CreatePaymentOutputDto::class);
    expect($outputDto->payment)->toBe($payment);
    expect($outputDto->notification)->toBe($notification);
    expect($outputDto->notification->getErrors())->toBe([]);
});

it('can create an instance of CreatePaymentOutputDto with null employee and error notifications', function () {
    $notification = new Notification();

    $notification->addError([
        'context' => 'test_error',
        'message' => 'test',
    ]);

    $outputDto = new CreatePaymentOutputDto(null, $notification);

    expect($outputDto)->toBeInstanceOf(CreatePaymentOutputDto::class);
    expect($outputDto->payment)->toBeNull();
    expect($outputDto->notification->getErrors())->toBe([
        [
            'context' => 'test_error',
            'message' => 'test',
        ],
    ]);
});

