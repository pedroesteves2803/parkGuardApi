<?php

use Src\Administration\Application\Dtos\Parking\GetParkingByIdInputDto;

describe('Test GetParkingByIdInputDto', function() {

    it('can create an instance of GetParkingByIdInputDto with valid data', function () {
        $id = 1;

        $inputDto = new GetParkingByIdInputDto(
            $id
        );

        expect($inputDto)->toBeInstanceOf(GetParkingByIdInputDto::class)
            ->and($inputDto->parkingId)->toBe($id);
    });

});
