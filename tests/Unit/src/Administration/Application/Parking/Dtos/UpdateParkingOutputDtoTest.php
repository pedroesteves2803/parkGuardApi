<?php

use Src\Administration\Application\Dtos\Parking\CreateParkingOutputDto;
use Src\Administration\Application\Dtos\Parking\UpdateParkingInputDto;
use Src\Administration\Application\Dtos\Parking\UpdateParkingOutputDto;
use Src\Administration\Domain\Factory\ParkingFactory;
use Src\Shared\Utils\Notification;

describe('Test UpdateParkingOutputDto', function() {
    it('can create an instance of UpdateParkingOutputDto with a valid parking', function () {
        $id = 1;
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

        $outputDto = new UpdateParkingOutputDto($parking, $notification);

        expect($outputDto)->toBeInstanceOf(UpdateParkingOutputDto::class)
            ->and($outputDto->parking)->toBe($parking)
            ->and($outputDto->notification)->toBe($notification)
            ->and($outputDto->notification->getErrors())->toBe([]);
    });

    it('can create an instance of UpdateParkingOutputDto with null parking and error notifications', function () {
        $notification = new Notification();

        $notification->addError([
            'context' => 'test_error',
            'message' => 'test',
        ]);

        $outputDto = new UpdateParkingOutputDto(null, $notification);

        expect($outputDto)->toBeInstanceOf(UpdateParkingOutputDto::class)
            ->and($outputDto->parking)->toBeNull()
            ->and($outputDto->notification->getErrors())->toBe([
                [
                    'context' => 'test_error',
                    'message' => 'test',
                ],
            ]);
    });
});
