<?php

use Src\Administration\Application\Dtos\Parking\GetAllParkingOutputDto;
use Src\Administration\Application\Dtos\Parking\UpdateParkingInputDto;
use Src\Administration\Application\Dtos\Parking\UpdateParkingOutputDto;
use Src\Administration\Application\Usecase\Parking\GetAllParkings;
use Src\Administration\Application\Usecase\Parking\UpdateParking;
use Src\Administration\Domain\Entities\Parking;
use Src\Administration\Domain\Factory\ParkingFactory;
use Src\Administration\Domain\Repositories\IParkingRepository;
use Src\Administration\Domain\ValueObjects\AdditionalHourPrice;
use Src\Administration\Domain\ValueObjects\Name;
use Src\Administration\Domain\ValueObjects\PricePerHour;
use Src\Shared\Utils\Notification;
use Illuminate\Support\Collection;


describe('GetAllParkingsTest', function () {

    beforeEach(function () {
        $this->repositoryMock = mock(IParkingRepository::class);
    });

    it('can retrieve all parkings', function () {
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

        $this->repositoryMock->shouldReceive('getAll')->once()->andReturn($parkings);

        $getAllParkings = new GetAllParkings($this->repositoryMock, $notification);

        $outputDto = $getAllParkings->execute();

        expect($outputDto)->toBeInstanceOf(GetAllParkingOutputDto::class)
            ->and($outputDto->parkings)->toEqual($parkings)
            ->and($outputDto->notification->getErrors())->toBeEmpty();
    });

    it('returns error notification when there are no parkings', function () {
        $notification = new Notification();

        $this->repositoryMock->shouldReceive('getAll')->once()->andReturnNull();

        $getAllParkings = new GetAllParkings($this->repositoryMock, $notification);

        $outputDto = $getAllParkings->execute();

        expect($outputDto)->toBeInstanceOf(GetAllParkingOutputDto::class)
            ->and($outputDto->parkings)->toBeNull()
            ->and($outputDto->notification->getErrors())->toBe([
                [
                    'context' => 'get_all_parkings',
                    'message' => 'Não possui estacionamentos!',
                ],
            ]);

    });

});
