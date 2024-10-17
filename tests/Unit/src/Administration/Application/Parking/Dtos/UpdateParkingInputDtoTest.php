<?php

use Src\Administration\Application\Dtos\Parking\UpdateParkingInputDto;

describe('Test UpdateParkingInputDto', function() {

    it('can create an instance of UpdateParkingInputDto with valid data', function () {
        $id = 1;
        $name = 'Parking 24h';
        $responsibleIdentification = '123456789';
        $responsibleName = 'Test Responsible';
        $pricePerHour = 20.0;
        $additionalHourPrice = 10.0;

        $inputDto = new UpdateParkingInputDto(
            $id,
            $name,
            $responsibleIdentification,
            $responsibleName,
            $pricePerHour,
            $additionalHourPrice
        );

        expect($inputDto)->toBeInstanceOf(UpdateParkingInputDto::class)
            ->and($inputDto->id)->toBe($id)
            ->and($inputDto->name)->toBe($name)
            ->and($inputDto->responsibleIdentification)->toBe($responsibleIdentification)
            ->and($inputDto->responsibleName)->toBe($responsibleName)
            ->and($inputDto->pricePerHour)->toBe($pricePerHour)
            ->and($inputDto->additionalHourPrice)->toBe($additionalHourPrice);
    });
});
