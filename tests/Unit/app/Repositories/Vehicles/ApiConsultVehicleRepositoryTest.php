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
    $this->repository = new ApiConsultVehicleRepository($this->clientMock);
});

it('returns vehicle data with pendings when API response is successful', function () {
    $licensePlate = new LicensePlate('ABC-1234');

    $outputDto = $this->repository->consult($licensePlate);

    expect($outputDto)->toBeInstanceOf(IConsultVehicleRepositoryOutputDto::class);
    if ($outputDto->manufacturer !== null) {
        expect($outputDto->manufacturer)->assertNotEmpty();
    } else {
        expect($outputDto->manufacturer)->toBeNull();
    }

    if ($outputDto->color !== null) {
        expect($outputDto->color)->assertNotEmpty();
    } else {
        expect($outputDto->color)->toBeNull();
    }

    if ($outputDto->model !== null) {
        expect($outputDto->model)->assertNotEmpty();
    } else {
        expect($outputDto->model)->toBeNull();
    }

    expect($outputDto->licensePlate)->toBe('ABC1234');

    $expectedPendings = [
        new Pending(null, new Type('Tipo1'), new Description('Descrição 1')),
        new Pending(null, new Type('Tipo2'), new Description('Descrição 2')),
        new Pending(null, new Type('Tipo3'), new Description('Descrição 3')),
        new Pending(null, new Type('Tipo4'), new Description('Descrição 4')),
    ];

    foreach ($outputDto->pendings as $index => $pending) {
        expect($pending->type->value())->toBe($expectedPendings[$index]->type->value());
        expect($pending->description->value())->toBe($expectedPendings[$index]->description->value());
    }
});

it('returns empty vehicle data when API request fails', function () {
    $licensePlate = new LicensePlate('ABC-1234');

    $outputDto = $this->repository->consult($licensePlate);

    expect($outputDto)->toBeInstanceOf(IConsultVehicleRepositoryOutputDto::class);
    expect($outputDto->manufacturer)->toBeNull();
    expect($outputDto->color)->toBeNull();
    expect($outputDto->model)->toBeNull();
    expect($outputDto->licensePlate)->toBe('ABC1234');
    expect($outputDto->pendings)->toBeEmpty();
});

