<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use DateTime;
use Illuminate\Http\Request;
use Src\Vehicles\Application\Vehicle\Dtos\CreateVehicleInputDto;
use Src\Vehicles\Application\Vehicle\CreateVehicle;

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

        dd($outputDto);

    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        //
    }
}
