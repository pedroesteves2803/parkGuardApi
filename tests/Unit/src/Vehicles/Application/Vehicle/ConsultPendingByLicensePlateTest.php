<?php

use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Vehicle\ConsultPendingByLicensePlate;
use Src\Vehicles\Application\Vehicle\Dtos\ConsultVehicleByLicensePlateInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\ConsultVehicleByLicensePlateOutputDto;
use Src\Vehicles\Domain\Entities\Pending;
use Src\Vehicles\Domain\Repositories\Dtos\IConsultVehicleRepositoryOutputDto;
use Src\Vehicles\Domain\Repositories\IConsultVehicleRepository;
use Src\Vehicles\Domain\ValueObjects\Description;
use Src\Vehicles\Domain\ValueObjects\Type;

beforeEach(function () {
    $this->repositoryConsultMock = mock(IConsultVehicleRepository::class);
});

test('should successfully query pending issues by license plate with data found', function () {
    $this->repositoryConsultMock->shouldReceive('consult')
        ->once()
        ->andReturn(new IConsultVehicleRepositoryOutputDto(
            'Toyota',
            'Azul',
            'Corolla',
            'ABC-1234',
            [
                new Pending(
                    null,
                    new Type('Tipo 1'),
                    new Description('Descrição 1'),
                ),
            ]
        ));

    $consultVehicle = new ConsultPendingByLicensePlate(
        $this->repositoryConsultMock,
        new Notification()
    );

    $inputDto = new ConsultVehicleByLicensePlateInputDto(
        'ABC-1234',
    );

    $outputDto = $consultVehicle->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(ConsultVehicleByLicensePlateOutputDto::class);
    expect($outputDto->manufacturer)->toBe('Toyota');
    expect($outputDto->color)->toBe('Azul');
    expect($outputDto->model)->toBe('Corolla');
    expect($outputDto->licensePlate)->toBe('ABC-1234');

    $expectedPendings = [
        new Pending(
            null,
            new Type('Tipo 1'),
            new Description('Descrição 1'),
        )
    ];

    expect(count($outputDto->pending))->toBe(count($expectedPendings));

    foreach ($outputDto->pending as $index => $pending) {
        expect($pending->type->value())->toBe($expectedPendings[$index]->type->value());
        expect($pending->description->value())->toBe($expectedPendings[$index]->description->value());
    }

    expect($outputDto->notification->getErrors())->toBeEmpty();
});

test('should successfully query pending issues by license plate with no vehicle data found', function () {
    $this->repositoryConsultMock->shouldReceive('consult')
        ->once()
        ->andReturn(new IConsultVehicleRepositoryOutputDto(
            null,
            null,
            null,
            'ABC-1234',
            [
                new Pending(
                    null,
                    new Type('Tipo 1'),
                    new Description('Descrição 1'),
                ),
            ]
        ));

    $consultVehicle = new ConsultPendingByLicensePlate(
        $this->repositoryConsultMock,
        new Notification()
    );

    $inputDto = new ConsultVehicleByLicensePlateInputDto(
        'ABC-1234',
    );

    $outputDto = $consultVehicle->execute($inputDto);

    expect($outputDto)->toBeInstanceOf(ConsultVehicleByLicensePlateOutputDto::class);
    expect($outputDto->manufacturer)->toBeNull();
    expect($outputDto->color)->toBeNull();
    expect($outputDto->model)->toBeNull();
    expect($outputDto->licensePlate)->toBe('ABC-1234');

    $expectedPendings = [
        new Pending(
            null,
            new Type('Tipo 1'),
            new Description('Descrição 1'),
        )
    ];

    expect(count($outputDto->pending))->toBe(count($expectedPendings));

    foreach ($outputDto->pending as $index => $pending) {
        expect($pending->type->value())->toBe($expectedPendings[$index]->type->value());
        expect($pending->description->value())->toBe($expectedPendings[$index]->description->value());
    }

    expect($outputDto->notification->getErrors())->toBeEmpty();
});
