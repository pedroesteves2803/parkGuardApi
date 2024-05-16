<?php

namespace App\Http\Controllers;

use App\Http\Resources\Vehicle\GetVehicleByIdResource;
use App\Http\Resources\Vehicle\CreateVehicleResource;
use App\Http\Resources\Vehicle\UpdateVehicleResource;
use App\Models\Vehicle;
use DateTime;
use Illuminate\Http\Request;
use Src\Vehicles\Application\Vehicle\Dtos\CreateVehicleInputDto;
use Src\Vehicles\Application\Vehicle\CreateVehicle;
use Src\Vehicles\Application\Vehicle\Dtos\GetVehicleInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\UpdateVehicleInputDto;
use Src\Vehicles\Application\Vehicle\GetVehicleById;
use Src\Vehicles\Application\Vehicle\UpdateVehicle;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        Request $request,
        CreateVehicle $createVehicle
    )
    {
        $inputDto = new CreateVehicleInputDto(
            $request->manufacturer,
            $request->color,
            $request->model,
            $request->licensePlate,
            new DateTime(),
            null,
        );

        $outputDto = $createVehicle->execute($inputDto);

        return new CreateVehicleResource(
            $outputDto
        );
    }

    /**
     * Display the specified resource.
     */
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
    )
    {
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        //
    }
}
