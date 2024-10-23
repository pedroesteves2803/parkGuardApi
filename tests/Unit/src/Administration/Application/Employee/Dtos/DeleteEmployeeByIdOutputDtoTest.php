<?php

use Src\Administration\Application\Dtos\Employee\DeleteEmployeeByIdOutputDto;
use Src\Shared\Utils\Notification;

it('can create an instance of DeleteEmployeeByIdOutputDto with null employee and empty notification', function () {
    $notification = new Notification();

    $outputDto = new DeleteEmployeeByIdOutputDto(null, $notification);

    expect($outputDto)->toBeInstanceOf(DeleteEmployeeByIdOutputDto::class);
    expect($outputDto->employee)->toBeNull();
    expect($outputDto->notification)->toBe($notification);
    expect($outputDto->notification->getErrors())->toBe([]);
});

it('can create an instance of DeleteEmployeeByIdOutputDto with null employee and error notifications', function () {
    $notification = new Notification();

    $notification->addError([
        'context' => 'test_error',
        'message' => 'test',
    ]);

    $outputDto = new DeleteEmployeeByIdOutputDto(null, $notification);

    expect($outputDto)->toBeInstanceOf(DeleteEmployeeByIdOutputDto::class)
        ->and($outputDto->employee)->toBeNull()
        ->and($outputDto->notification->getErrors())->toBe([
            [
                'context' => 'test_error',
                'message' => 'test',
            ],
        ]);
});
