<?php

use Src\Administration\Application\Dtos\Parking\CreateParkingOutputDto;
use Src\Administration\Application\Dtos\Parking\DeleteParkingOutputDto;
use Src\Administration\Domain\Factory\ParkingFactory;
use Src\Shared\Utils\Notification;

describe('Test DeleteParkingOutputDto', function() {

    it('can create an instance of DeleteParkingOutputDto with a valid parking', function () {
        $notification = new Notification();

        $outputDto = new DeleteParkingOutputDto(null, $notification);

        expect($outputDto)->toBeInstanceOf(DeleteParkingOutputDto::class)
            ->and($outputDto->parking)->toBeNull()
            ->and($outputDto->notification)->toBe($notification)
            ->and($outputDto->notification->getErrors())->toBe([]);
    });

    it('can create an instance of DeleteParkingOutputDto with null parking and error notifications', function () {
        $notification = new Notification();

        $notification->addError([
            'context' => 'test_error',
            'message' => 'test',
        ]);

        $outputDto = new DeleteParkingOutputDto(null, $notification);

        expect($outputDto)->toBeInstanceOf(DeleteParkingOutputDto::class)
            ->and($outputDto->parking)->toBeNull()
            ->and($outputDto->notification->getErrors())->toBe([
                [
                    'context' => 'test_error',
                    'message' => 'test',
                ],
            ]);
    });
});
