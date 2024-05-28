<?php

namespace App\Http\Controllers;

use App\Http\Resources\Vehicle\CreateVehicleResource;
use App\Http\Resources\Vehicle\ExitVehicleResource;
use App\Http\Resources\Vehicle\GetAllVehiclesResource;
use App\Http\Resources\Vehicle\GetVehicleByIdResource;
use App\Http\Resources\Vehicle\UpdateVehicleResource;
use Illuminate\Http\Request;
use Src\Vehicles\Application\Vehicle\AddPending;
use Src\Vehicles\Application\Vehicle\ConsultPendingByLicensePlate;
use Src\Vehicles\Application\Vehicle\CreateVehicle;
use Src\Vehicles\Application\Vehicle\Dtos\AddPendingInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\ConsultVehicleByLicensePlateInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\CreateVehicleInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\ExitVehicleInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\GetVehicleInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\UpdateVehicleInputDto;
use Src\Vehicles\Application\Vehicle\ExitVehicle;
use Src\Vehicles\Application\Vehicle\GetAllVehicles;
use Src\Vehicles\Application\Vehicle\GetVehicleById;
use Src\Vehicles\Application\Vehicle\UpdateVehicle;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;

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
        CreateVehicle $createVehicle,
    ) {
        $createInputDto = new CreateVehicleInputDto(
            $request->licensePlate,
        );

        $createOutputDto = $createVehicle->execute($createInputDto);

        return new CreateVehicleResource(
            $createOutputDto
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

    public function update(
        Request $request,
        int $id,
        UpdateVehicle $updateVehicle
    ) {
        $inputDto = new UpdateVehicleInputDto(
            $id,
            $request->manufacturer,
            $request->color,
            $request->model,
            $request->licensePlate
        );

        $outputDto = $updateVehicle->execute($inputDto);

        return new UpdateVehicleResource($outputDto);
    }

    public function exit(
        string $licensePlate,
        ExitVehicle $exitVehicle
    ) {
        $inputDto = new ExitVehicleInputDto(
            new LicensePlate($licensePlate)
        );

        $outputDto = $exitVehicle->execute($inputDto);

        return new ExitVehicleResource($outputDto);
    }
}
