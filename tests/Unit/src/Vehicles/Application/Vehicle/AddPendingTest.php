<?php

use App\Models\Vehicle as ModelsVehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Vehicle\AddPending;
use Src\Vehicles\Application\Vehicle\Dtos\AddPendingInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\AddPendingOutputDto;
use Src\Vehicles\Domain\Entities\Consult;
use Src\Vehicles\Domain\Entities\Pending;
use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\Repositories\IVehicleRepository;
use Src\Vehicles\Domain\ValueObjects\Color;
use Src\Vehicles\Domain\ValueObjects\DepartureTimes;
use Src\Vehicles\Domain\ValueObjects\Description;
use Src\Vehicles\Domain\ValueObjects\EntryTimes;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Manufacturer;
use Src\Vehicles\Domain\ValueObjects\Model;
use Src\Vehicles\Domain\ValueObjects\Type;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->repositoryMock = mock(IVehicleRepository::class);
});

it('successfully adds a pending to a vehicle', function () {
    $vehicle = new Vehicle(
        1,
        new Manufacturer('Toyota'),
        new Color('Azul'),
        new Model('Corolla'),
        new LicensePlate('ABC-1234'),
        new EntryTimes(new DateTime('2024-05-12 08:00:00')),
        new DepartureTimes(new DateTime('2024-05-12 17:00:00'))
    );

    $notification = new Notification();

    $this->repositoryMock->shouldReceive('existVehicle')->once()->andReturnTrue();

    $collectionPendings = new Collection();
    $pending = new Pending(
        null,
        new Type('Type 1'),
        new Description('Description 1')
    );
    $collectionPendings->push($pending);

    $this->repositoryMock->shouldReceive('addPending')
        ->once()
        ->andReturn($collectionPendings);

    $addPending = new AddPending($this->repositoryMock, $notification);

    $inputDto = new AddPendingInputDto(
        $vehicle,
    );

    $outputDto = $addPending->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(AddPendingOutputDto::class);
    expect($outputDto->pendings)->toBeInstanceOf(Collection::class);
    expect($outputDto->notification->getErrors())->toBeEmpty();

    expect($pending->type()->value())->toBe('Type 1');
    expect($pending->description()->value())->toBe('Description 1');
});

it('fails to add a pending when the vehicle does not exist', function () {
    $vehicle = new Vehicle(
        1,
        new Manufacturer('Toyota'),
        new Color('Azul'),
        new Model('Corolla'),
        new LicensePlate('ABC-1234'),
        new EntryTimes(new DateTime('2024-05-12 08:00:00')),
        new DepartureTimes(new DateTime('2024-05-12 17:00:00'))
    );

    $notification = new Notification();

    $this->repositoryMock->shouldReceive('existVehicle')->once()->andReturnFalse();

    $addPending = new AddPending($this->repositoryMock, $notification);

    $inputDto = new AddPendingInputDto(
        $vehicle,
    );

    $outputDto = $addPending->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(AddPendingOutputDto::class);
    expect($outputDto->pendings)->toBeNull();
    expect($outputDto->notification->getErrors())->toBe([
        [
            'context' => 'license_plate_already_exists',
            'message' => 'Veiculo não cadastrado!',
        ],
    ]);
});
