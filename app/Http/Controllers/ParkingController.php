<?php

namespace App\Http\Controllers;

use App\Http\Requests\Parking\CreateParkingRequest;
use App\Http\Resources\Parking\CreateParkingResource;
use Illuminate\Http\Request;
use Src\Administration\Application\Dtos\CreateParkingInputDto;
use Src\Administration\Application\Usecase\CreateParking;

class ParkingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        CreateParkingRequest $request,
        CreateParking $createParking
    )
    {
        $inputDto = new CreateParkingInputDto(
            $request->name,
            $request->responsibleIdentification,
            $request->responsibleName,
            $request->pricePerHour,
            $request->additionalHourPrice,
        );

        $createParkingOutput = $createParking->execute($inputDto);

        return new CreateParkingResource($createParkingOutput);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
