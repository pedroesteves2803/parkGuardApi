<?php

use Illuminate\Support\Collection;
use Src\Payments\Application\Payment\Dtos\GetAllPaymentsOutputDto;
use Src\Payments\Domain\Entities\Payment;
use Src\Shared\Utils\Notification;

it('can retrieve all payments', function () {
    $notification = new Notification();

    $payments = new Collection();

    for ($i = 0; $i < 10; $i++) {
        $payments->push(
            mock(Payment::class)
        );
    }

    $notification = new Notification();

    $outputDto = new GetAllPaymentsOutputDto($payments, $notification);

    expect($outputDto)->toBeInstanceOf(GetAllPaymentsOutputDto::class);
    expect($outputDto->payments)->toBe($payments);
    $this->assertCount($payments->count(), $outputDto->payments);
    expect($outputDto->notification)->toBe($notification);
    expect($outputDto->notification->getErrors())->toBe([]);
});

it('can create an instance of GetAllPaymentsOutputDto with null payment and error notifications', function () {
    $notification = new Notification();

    $notification->addError([
        'context' => 'test_error',
        'message' => 'test',
    ]);

    $outputDto = new GetAllPaymentsOutputDto(null, $notification);

    expect($outputDto)->toBeInstanceOf(GetAllPaymentsOutputDto::class);
    expect($outputDto->payments)->toBeNull();
    expect($outputDto->notification->getErrors())->toBe([
        [
            'context' => 'test_error',
            'message' => 'test',
        ],
    ]);
});
