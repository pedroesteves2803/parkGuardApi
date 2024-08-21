<?php

namespace App\Repositories\Vehicles;

use GuzzleHttp\Client;
use Src\Vehicles\Domain\Entities\Pending;
use Src\Vehicles\Domain\Repositories\Dtos\IConsultVehicleRepositoryOutputDto;
use Src\Vehicles\Domain\Repositories\IConsultVehicleRepository;
use Src\Vehicles\Domain\ValueObjects\Description;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Type;

class ApiConsultVehicleRepository implements IConsultVehicleRepository
{
    private Client $client;

    public function __construct()
    {
        $options = [
            'verify' => env('VERIFY_SSL') ?? true,
        ];

        $this->client = new Client($options);
    }

    public function consult(LicensePlate $licensePlate): IConsultVehicleRepositoryOutputDto
    {
        $headers = [
            'Authorization' => env('API_TOKEN'),
            'Content-Type' => 'application/json',
            'DeviceToken' => env('DEVICE_TOKEN'),
        ];

        $query = ['placa' => $licensePlate->value()];

        try {
            $response = $this->client->request('POST', env('API_URL'), [
                'headers' => $headers,
                'query' => $query,
            ]);

            $vehicleData = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

            if (isset($vehicleData['error']) && $vehicleData['error']) {
                return $this->getEmptyVehicleData($licensePlate);
            }

            $pendings = [];

            $manufacturer = $vehicleData['response']['MARCA'] ?? null;
            $color = $vehicleData['response']['cor'] ?? null;
            $model = $vehicleData['response']['MODELO'] ?? null;
            $licensePlate = $vehicleData['response']['placa'] ?? $licensePlate->value();

            for ($i = 1; $i <= 4; $i++) {
                $pending = $vehicleData['response']['extra']['restricao'.$i]['restricao'] ?? '';
                $type = 'Tipo'.$i;
                $description = $pending;


                $pendings[] = new Pending(
                    null,
                    new Type($type),
                    $description === "" ? null : new Description($description)
                );
            }

            return new IConsultVehicleRepositoryOutputDto(
                $manufacturer,
                $color,
                $model,
                $licensePlate,
                $pendings,
            );
        } catch (\Exception $e) {
            return $this->getEmptyVehicleData($licensePlate);
        }
    }

    private function getEmptyVehicleData($licensePlate): IConsultVehicleRepositoryOutputDto
    {
        return new IConsultVehicleRepositoryOutputDto(
            null,
            null,
            null,
            $licensePlate,
            [],
        );
    }
}
