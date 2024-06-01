<?php

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Src\Vehicles\Domain\Entities\Pending;
use Src\Vehicles\Domain\Repositories\Dtos\IConsultVehicleRepositoryOutputDto;
use Src\Vehicles\Domain\ValueObjects\Description;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Type;
use App\Repositories\Vehicles\ApiConsultVehicleRepository;

beforeEach(function () {
    $this->clientMock = mock(Client::class);
    $this->repositoryConsultMock = mock(new ApiConsultVehicleRepository($this->clientMock));
});

it('returns vehicle data with pendings when API response is successful', function () {
    $this->repositoryConsultMock->shouldReceive('consult')->once()->andReturn(
        new IConsultVehicleRepositoryOutputDto(
            "VW",
            "Prata",
            "Golf",
            'ABC1234',
            [
                new Pending(
                    null,
                    new Type("Tipo 1"),
                    new Description("SEM RESTRICAO")
                ),
                new Pending(
                    null,
                    new Type("Tipo 2"),
                    new Description("SEM RESTRICAO")
                ),
                new Pending(
                    null,
                    new Type("Tipo 3"),
                    new Description("SEM RESTRICAO")
                ),
                new Pending(
                    null,
                    new Type("Tipo 4"),
                    new Description("SEM RESTRICAO")
                ),
            ],
        )
    );

    $licensePlate = new LicensePlate('ABC-1234');

    $outputDto = $this->repositoryConsultMock->consult($licensePlate);

    expect($outputDto)->toBeInstanceOf(IConsultVehicleRepositoryOutputDto::class);

    if ($outputDto->manufacturer !== null) {
        expect($outputDto->manufacturer)->not()->toBeEmpty();
    } else {
        expect($outputDto->manufacturer)->toBeNull();
    }

    if ($outputDto->color !== null) {
        expect($outputDto->color)->not()->toBeEmpty();
    } else {
        expect($outputDto->color)->toBeNull();
    }

    if ($outputDto->model !== null) {
        expect($outputDto->model)->not()->toBeEmpty();
    } else {
        expect($outputDto->model)->toBeNull();
    }

    expect($outputDto->licensePlate)->toBe('ABC1234');

    $expectedPendings = [
        new Pending(null, new Type('Tipo 1'), new Description('SEM RESTRICAO')),
        new Pending(null, new Type('Tipo 2'), new Description('SEM RESTRICAO')),
        new Pending(null, new Type('Tipo 3'), new Description('SEM RESTRICAO')),
        new Pending(null, new Type('Tipo 4'), new Description('SEM RESTRICAO')),
    ];

    foreach ($outputDto->pendings as $index => $pending) {
        expect($pending->type->value())->toBe($expectedPendings[$index]->type->value());
        expect($pending->description->value())->toBe($expectedPendings[$index]->description->value());
    }
});

it('returns empty vehicle data when API request fails', function () {
    $this->repositoryConsultMock->shouldReceive('consult')->once()->andReturn(
        new IConsultVehicleRepositoryOutputDto(
            null,
            null,
            null,
            'ABC1234',
            [
                new Pending(
                    null,
                    new Type("Tipo 1"),
                    null
                ),
                new Pending(
                    null,
                    new Type("Tipo 2"),
                    null
                ),
                new Pending(
                    null,
                    new Type("Tipo 3"),
                    null
                ),
                new Pending(
                    null,
                    new Type("Tipo 4"),
                    null
                ),
            ],
        )
    );

    $licensePlate = new LicensePlate('ABC-1234');

    $outputDto = $this->repositoryConsultMock->consult($licensePlate);

    expect($outputDto)->toBeInstanceOf(IConsultVehicleRepositoryOutputDto::class);
    expect($outputDto->manufacturer)->toBeNull();
    expect($outputDto->color)->toBeNull();
    expect($outputDto->model)->toBeNull();
    expect($outputDto->licensePlate)->toBe('ABC1234');

    $expectedPendings = [
        new Pending(null, new Type('Tipo 1'), null),
        new Pending(null, new Type('Tipo 2'), null),
        new Pending(null, new Type('Tipo 3'), null),
        new Pending(null, new Type('Tipo 4'), null),
    ];


    foreach ($outputDto->pendings as $index => $pending) {
        expect($pending->type->value())->toBe($expectedPendings[$index]->type->value());
        expect($pending->description)->toBe($expectedPendings[$index]->description);
    }
});

