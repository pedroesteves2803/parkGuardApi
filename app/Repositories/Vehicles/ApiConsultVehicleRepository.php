<?php

namespace App\Repositories\Vehicles;

use GuzzleHttp\Client;
use Src\Vehicles\Domain\Entities\Consult;
use Src\Vehicles\Domain\Entities\Pending;
use Src\Vehicles\Domain\Repositories\IConsultVehicleRepository;
use Src\Vehicles\Domain\ValueObjects\Color;
use Src\Vehicles\Domain\ValueObjects\Description;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Manufacturer;
use Src\Vehicles\Domain\ValueObjects\Model;
use Src\Vehicles\Domain\ValueObjects\Type;

final class ApiConsultVehicleRepository implements IConsultVehicleRepository
{
    private Client $client;

    public function __construct(Client $client)
    {
        $client = new \GuzzleHttp\Client([
            'verify' => false,
        ]);

        $this->client = $client;
    }

    public function consult(LicensePlate $licensePlate): ?Consult
    {
        $headers = [
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL2NsdXN0ZXIuYXBpZ3JhdGlzLmNvbS9hcGkvdjIvbG9naW4iLCJpYXQiOjE3MTY4NDE5MzgsImV4cCI6MTc0ODM3NzkzOCwibmJmIjoxNzE2ODQxOTM4LCJqdGkiOiJqVFl3Y2c0Uzcwb2JUSnB6Iiwic3ViIjoiODUyMiIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.ZaLDWRwezjU3ChnHAfK3uWa3ck50NACtFiHQtho-dtk',
            'Content-Type' => 'application/json',
            'DeviceToken' => '7995afa6-0135-4ae2-ba72-d223a7a70618',
        ];

        $response = $this->client->request('GET', 'https://cluster.apigratis.com/api/v2/vehicles/dados', [
            'headers' => $headers,
            'query' => ['placa' => $licensePlate->value()],
        ]);

        $vehicleData = json_decode($response->getBody()->getContents(), true);

        if ($vehicleData['error'] === true) {
            return null;
        }

        $consult = new Consult(
            new Manufacturer($vehicleData['response']['MARCA']),
            new Color($vehicleData['response']['cor']),
            new Model($vehicleData['response']['MODELO']),
            new LicensePlate($vehicleData['response']['placa']),
        );

        for ($i = 0; $i < 4; $i++) {
            $consult->addPending(
                new Pending(
                    null,
                    new Type('Tipo'.$i),
                    new Description($vehicleData['response']['extra']['restricao'.$i + 1]['restricao'])
                )
            );
        }

        return $consult;

    }
}
