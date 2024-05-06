<?php

namespace App\Http\Controllers;

use App\Http\Resources\Employee\CreateEmployeeResource;
use App\Http\Resources\Employee\GetAllEmpoyeesResource;
use App\Http\Resources\Employee\GetEmpoyeeByIdResource;
use App\Models\Employee;
use Illuminate\Http\Request;
use Src\Administration\Application\Employee\CreateEmployee;
use Src\Administration\Application\Employee\Dtos\CreateEmployeeInputDto;
use Src\Administration\Application\Employee\Dtos\GetEmployeeByIdInputDto;
use Src\Administration\Application\Employee\GetAllEmployees;
use Src\Administration\Application\Employee\GetEmployeeById;

class EmployeeController extends Controller
{
    public function index(
        GetAllEmployees $getAllEmployees
    ) {
        $output = $getAllEmployees->execute();

        return new GetAllEmpoyeesResource($output);
    }

    public function store(
        Request $request,
        CreateEmployee $createEmployee
    ) {
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

    public function show(
        int $id,
        GetEmployeeById $getEmployeeById
    ) {
        $inputDto = new GetEmployeeByIdInputDto(
            $id,
        );

        $output = $getEmployeeById->execute($inputDto);

        return new GetEmpoyeeByIdResource($output);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
    }
}
