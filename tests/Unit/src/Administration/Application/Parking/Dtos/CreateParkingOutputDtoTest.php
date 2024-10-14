<?php

use Src\Administration\Application\Dtos\CreateEmployeeOutputDto;
use Src\Administration\Application\Dtos\CreateParkingOutputDto;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\Factory\ParkingFactory;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\Name;
use Src\Administration\Domain\ValueObjects\Password;
use Src\Administration\Domain\ValueObjects\Type;
use Src\Shared\Utils\Notification;

it('can create an instance of CreateParkingOutputDto with a valid parking', function () {
    $id = '1';
    $name = 'Parking 24h';
    $responsibleIdentification = '123456789';
    $responsibleName = 'Test Responsible';
    $pricePerHour = 20.0;
    $additionalHourPrice = 10.0;

    $parkingFactory = new ParkingFactory();

    $parking = $parkingFactory->create(
        $id,
        $name,
        $responsibleIdentification,
        $responsibleName,
        $pricePerHour,
        $additionalHourPrice,
    );

    $notification = new Notification();

    $outputDto = new CreateParkingOutputDto($parking, $notification);

    expect($outputDto)->toBeInstanceOf(CreateParkingOutputDto::class)
        ->and($outputDto->parking)->toBe($parking)
        ->and($outputDto->notification)->toBe($notification)
        ->and($outputDto->notification->getErrors())->toBe([]);
});

it('can create an instance of CreateParkingOutputDto with null parking and error notifications', function () {
    $notification = new Notification();

    $notification->addError([
        'context' => 'test_error',
        'message' => 'test',
    ]);

    $outputDto = new CreateParkingOutputDto(null, $notification);

    expect($outputDto)->toBeInstanceOf(CreateParkingOutputDto::class)
        ->and($outputDto->parking)->toBeNull()
        ->and($outputDto->notification->getErrors())->toBe([
            [
                'context' => 'test_error',
                'message' => 'test',
            ],
        ]);
});
