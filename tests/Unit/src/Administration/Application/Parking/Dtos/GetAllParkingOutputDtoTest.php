<?php

use Src\Administration\Application\Dtos\Parking\GetAllParkingOutputDto;
use Src\Administration\Domain\Factory\ParkingFactory;
use Src\Shared\Utils\Notification;
use Illuminate\Support\Collection;

describe('Test GetAllParkingOutputDto', function() {

    it('can create an instance of GetAllParkingOutputDto with a valid parking', function () {
        $parkings = new Collection();

        for ($i = 0; $i < 10; ++$i) {
            $id = $i + 1;
            $name = 'Parking 24h ' . ($i + 1);
            $responsibleIdentification = '123456789' . ($i + 1);
            $responsibleName = 'Test Responsible ' . ($i + 1);
            $pricePerHour = 20.0;
            $additionalHourPrice = 10.0;

            $parkingFactory = new ParkingFactory();

            $parkings->push(
                $parkingFactory->create(
                    $id,
                    $name,
                    $responsibleIdentification,
                    $responsibleName,
                    $pricePerHour,
                    $additionalHourPrice,
                )
            );
        }

        $notification = new Notification();

        $outputDto = new GetAllParkingOutputDto($parkings, $notification);

        expect($outputDto)->toBeInstanceOf(GetAllParkingOutputDto::class)
            ->and($outputDto->parkings)->toBe($parkings);
        $this->assertCount($parkings->count(), $outputDto->parkings);
        expect($outputDto->notification)->toBe($notification)
            ->and($outputDto->notification->getErrors())->toBe([]);
    });

    it('can create an instance of GetAllParkingOutputDto with null employee and error notifications', function () {
        $notification = new Notification();

        $notification->addError([
            'context' => 'test_error',
            'message' => 'test',
        ]);

        $outputDto = new GetAllParkingOutputDto(null, $notification);

        expect($outputDto)->toBeInstanceOf(GetAllParkingOutputDto::class)
            ->and($outputDto->parkings)->toBeNull()
            ->and($outputDto->notification->getErrors())->toBe([
                [
                    'context' => 'test_error',
                    'message' => 'test',
                ],
            ]);
    });
});
