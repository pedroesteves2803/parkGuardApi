<?php

namespace App\Http\Controllers;

use App\Http\Resources\Vehicle\CreateVehicleResource;
use App\Http\Resources\Vehicle\GetAllVehiclesResource;
use App\Http\Resources\Vehicle\GetVehicleByIdResource;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Src\Vehicles\Application\Vehicle\CreateVehicle;
use Src\Vehicles\Application\Vehicle\Dtos\CreateVehicleInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\GetVehicleInputDto;
use Src\Vehicles\Application\Vehicle\GetAllVehicles;
use Src\Vehicles\Application\Vehicle\GetVehicleById;

class VehicleController extends Controller
{
    public function index(
        GetAllVehicles $getAllVehicles
    ) {
        $output = $getAllVehicles->execute();

        return new GetAllVehiclesResource($output);
    }

    public function store(
        Request $request,
        CreateVehicle $createVehicle
    ) {
        $inputDto = new CreateVehicleInputDto(
            $request->manufacturer,
            $request->color,
            $request->model,
            $request->licensePlate,
            new \DateTime(),
            null,
        );

        $outputDto = $createVehicle->execute($inputDto);

        return new CreateVehicleResource(
            $outputDto
        );
    }

    public function show(
        int $id,
        GetVehicleById $getVehicleById
    ) {
        $inputDto = new GetVehicleInputDto(
            $id,
        );

        $outputDto = $getVehicleById->execute($inputDto);

        return new GetVehicleByIdResource($outputDto);
    }

    public function update(Request $request, Vehicle $vehicle)
    {
    }

    public function destroy(Vehicle $vehicle)
    {
    }
}
