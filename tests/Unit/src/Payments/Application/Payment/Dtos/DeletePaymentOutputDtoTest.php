<?php

use Src\Payments\Application\Dtos\DeletePaymentOutputDto;
use Src\Payments\Domain\Entities\Payment;
use Src\Shared\Utils\Notification;

it('can create an instance of DeletePaymentOutputDto with valid data', function () {
    $payment = mock(Payment::class);

    $outputDto = new DeletePaymentOutputDto(
        $payment,
        new Notification()
    );

    expect($outputDto)->toBeInstanceOf(DeletePaymentOutputDto::class);
    expect($outputDto->payment)->toBe($payment);
    expect($outputDto->notification->getErrors())->toBe([]);
});

it('can create an instance of DeletePaymentOutputDto with null employee and error notifications', function () {
    $notification = new Notification();

    $notification->addError([
        'context' => 'test_error',
        'message' => 'test',
    ]);

    $outputDto = new DeletePaymentOutputDto(null, $notification);

    expect($outputDto)->toBeInstanceOf(DeletePaymentOutputDto::class);
    expect($outputDto->payment)->toBeNull();
    expect($outputDto->notification->getErrors())->toBe([
        [
            'context' => 'test_error',
            'message' => 'test',
        ],
    ]);
});
