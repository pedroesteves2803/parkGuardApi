<?php
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Src\Vehicles\Domain\Entities\Pending;
use Src\Vehicles\Domain\Repositories\Dtos\IConsultVehicleRepositoryOutputDto;
use Src\Vehicles\Domain\ValueObjects\Description;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Type;
use App\Repositories\Vehicles\ApiConsultVehicleRepository;
use Illuminate\Support\Facades\Config;

beforeEach(function () {
    // Mocka o cliente HTTP
    $this->clientMock = mock(Client::class);

    // Mocka as configurações usando o facade Config
    Config::set('park-guard.api_token', 'mocked-token');
    Config::set('park-guard.api_url', 'http://mocked-url');
    Config::set('park-guard.api_device_token', 'mocked-device-token');
    Config::set('park-guard.api_verify_ssl', false);

    $this->repositoryConsult = new ApiConsultVehicleRepository($this->clientMock);
});

it('returns vehicle data with pendings when API response is successful', function () {
    $apiResponse = [
        'response' => [
            'MARCA' => 'VW',
            'cor' => 'Prata',
            'MODELO' => 'Golf',
            'placa' => 'ABC1234',
            'extra' => [
                'restricao1' => ['restricao' => 'SEM RESTRICAO'],
                'restricao2' => ['restricao' => 'SEM RESTRICAO'],
                'restricao3' => ['restricao' => 'SEM RESTRICAO'],
                'restricao4' => ['restricao' => 'SEM RESTRICAO'],
            ]
        ]
    ];

    // Mocka a resposta da API
    $this->clientMock->shouldReceive('request')->once()->andReturn(
        new Response(200, [], json_encode($apiResponse))
    );

    $licensePlate = new LicensePlate('ABC-1234');
    $outputDto = $this->repositoryConsult->consult($licensePlate);

    // Verifica as propriedades do objeto retornado
    expect($outputDto)->toBeInstanceOf(IConsultVehicleRepositoryOutputDto::class);
    expect($outputDto->manufacturer)->toBe('VW');
    expect($outputDto->color)->toBe('Prata');
    expect($outputDto->model)->toBe('Golf');
    expect($outputDto->licensePlate)->toBe('ABC1234');

    $expectedPendings = [
        new Pending(null, new Type('Tipo1'), new Description('SEM RESTRICAO')),
        new Pending(null, new Type('Tipo2'), new Description('SEM RESTRICAO')),
        new Pending(null, new Type('Tipo3'), new Description('SEM RESTRICAO')),
        new Pending(null, new Type('Tipo4'), new Description('SEM RESTRICAO')),
    ];

    foreach ($outputDto->pending as $index => $pending) {
        expect($pending->type->value())->toBe($expectedPendings[$index]->type->value())
            ->and($pending->description->value())->toBe($expectedPendings[$index]->description->value());
    }
});

it('returns empty vehicle data when API request fails', function () {
    // Mocka a falha na requisição da API
    $this->clientMock->shouldReceive('request')->once()->andThrow(new Exception());

    $licensePlate = new LicensePlate('ABC-1234');
    $outputDto = $this->repositoryConsult->consult($licensePlate);

    // Verifica as propriedades do objeto retornado
    expect($outputDto)->toBeInstanceOf(IConsultVehicleRepositoryOutputDto::class);
    expect($outputDto->manufacturer)->toBeNull();
    expect($outputDto->color)->toBeNull();
    expect($outputDto->model)->toBeNull();
    expect($outputDto->licensePlate)->toBe('ABC1234');

    foreach ($outputDto->pending as $pending) {
        expect($pending->description)->toBeNull();
    }
});
