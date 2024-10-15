<?php

namespace App\Http\Controllers;

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
        Request $request,
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

        $output = $createParking->execute($inputDto);

        dd($output);
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
