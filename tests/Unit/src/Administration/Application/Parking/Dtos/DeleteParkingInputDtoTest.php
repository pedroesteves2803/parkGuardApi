<?php


use Src\Administration\Application\Dtos\Parking\DeleteParkingInputDto;

describe('Test DeleteParkingInputDto', function() {

    it('can create an instance of DeleteParkingInputDto with valid data', function () {
        $id = 1;

        $inputDto = new DeleteParkingInputDto(
            $id,
        );

        expect($inputDto)->toBeInstanceOf(DeleteParkingInputDto::class)
            ->and($inputDto->parkingId)->toBe($id);
    });

});
