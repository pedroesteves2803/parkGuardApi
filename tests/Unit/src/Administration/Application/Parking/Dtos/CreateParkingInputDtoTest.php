<?php


use Src\Administration\Application\Dtos\Parking\CreateParkingInputDto;

describe('Test CreateParkingInputDto', function() {

    it('can create an instance of CreateParkingInputDto with valid data', function () {
        $name = 'Parking 24h';
        $responsibleIdentification = '123456789';
        $responsibleName = 'Test Responsible';
        $pricePerHour = 20.0;
        $additionalHourPrice = 10.0;

        $inputDto = new CreateParkingInputDto(
            $name,
            $responsibleIdentification,
            $responsibleName,
            $pricePerHour,
            $additionalHourPrice
        );

        expect($inputDto)->toBeInstanceOf(CreateParkingInputDto::class)
            ->and($inputDto->name)->toBe($name)
            ->and($inputDto->responsibleIdentification)->toBe($responsibleIdentification)
            ->and($inputDto->responsibleName)->toBe($responsibleName)
            ->and($inputDto->pricePerHour)->toBe($pricePerHour)
            ->and($inputDto->additionalHourPrice)->toBe($additionalHourPrice);
    });

});
