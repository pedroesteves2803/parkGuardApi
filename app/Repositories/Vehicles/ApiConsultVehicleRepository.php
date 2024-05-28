<?php

namespace App\Repositories\Vehicles;

use GuzzleHttp\Client;
use Src\Vehicles\Domain\Entities\Consult;
use Src\Vehicles\Domain\Entities\Pending;
use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\Repositories\IConsultVehicleRepository;
use Src\Vehicles\Domain\ValueObjects\Color;
use Src\Vehicles\Domain\ValueObjects\Description;
use Src\Vehicles\Domain\ValueObjects\EntryTimes;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;
use Src\Vehicles\Domain\ValueObjects\Manufacturer;
use Src\Vehicles\Domain\ValueObjects\Model;
use Src\Vehicles\Domain\ValueObjects\Type;
use Dotenv\Dotenv;

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

    public function consult(LicensePlate $licensePlate): ?Vehicle
    {
        $headers = [
            'Authorization' => env('API_TOKEN'),
            'Content-Type' => 'application/json',
            'DeviceToken' => env('DEVICE_TOKEN'),
        ];

        $query = ['placa' => $licensePlate->value()];

        // try {
            $response = $this->client->request('POST', env('API_URL'), [
                'headers' => $headers,
                'query' => $query,
            ]);

            $vehicleData = json_decode($response->getBody()->getContents(), true);

            if (isset($vehicleData['error']) && $vehicleData['error']) {
                return null;
            }

            $manufacturer = new Manufacturer($vehicleData['response']['MARCA']) ?? null;
            $color = new Color($vehicleData['response']['cor']) ?? null;
            $model = new Model($vehicleData['response']['MODELO'])?? null;
            $licensePlate = new LicensePlate($vehicleData['response']['placa'])?? null;
            $entryTimes = new EntryTimes(new \DateTime());

            $vehicle = new Vehicle(null, $manufacturer, $color, $model, $licensePlate, $entryTimes, null);

            for ($i = 1; $i <= 4; $i++) {
                $restricao = $vehicleData['response']['extra']['restricao' . $i]['restricao'] ?? '';
                $type = new Type('Tipo' . $i);
                $description = new Description($restricao);
                $vehicle->addPending(new Pending(null, $type, $description));
            }

            return $vehicle;
        // } catch (\Exception $e) {
        //     return null;
        // }
    }
}
