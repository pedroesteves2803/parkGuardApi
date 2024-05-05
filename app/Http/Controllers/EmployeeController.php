<?php

namespace App\Http\Controllers;

use App\Http\Resources\Employee\CreateEmployeeResource;
use App\Http\Resources\Employee\CreateResource;
use App\Models\Employee;
use Illuminate\Http\Request;
use Src\Administration\Application\Employee\CreateEmployee;
use Src\Administration\Application\Employee\Dtos\CreateEmployeeInputDto;

class EmployeeController extends Controller
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
        CreateEmployee $createEmployee
    )
    {

        $inputDto = new CreateEmployeeInputDto(
            null,
            $request->name,
            $request->email,
            $request->password,
            $request->type
        );

        $output = $createEmployee->execute($inputDto);

        return new CreateEmployeeResource(
            $output
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        //
    }
}
