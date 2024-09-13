<?php

use Src\Payments\Application\Dtos\CalculateValueOutputDto;
use Src\Shared\Utils\Notification;

it('can create an instance of CalculateValueOutputDto with a valid vehicle', function () {
    $notification = new Notification();
    $totalToPay = 1000;

    $outputDto = new CalculateValueOutputDto($totalToPay, $notification);

    expect($outputDto)->toBeInstanceOf(CalculateValueOutputDto::class)
        ->and($outputDto->totalToPay)->toBe($totalToPay)
        ->and($outputDto->notification)->toBe($notification)
        ->and($outputDto->notification->getErrors())->toBe([]);
});

it('can create an instance of CalculateValueOutputDto with null vehicle and error notifications', function () {
    $notification = new Notification();

    $notification->addError([
        'context' => 'test_error',
        'message' => 'test',
    ]);

    $outputDto = new CalculateValueOutputDto(null, $notification);

    expect($outputDto)->toBeInstanceOf(CalculateValueOutputDto::class)
        ->and($outputDto->totalToPay)->toBeNull()
        ->and($outputDto->notification->getErrors())->toBe([
            [
                'context' => 'test_error',
                'message' => 'test',
            ],
        ]);
});

