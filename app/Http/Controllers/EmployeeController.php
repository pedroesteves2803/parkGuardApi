<?php

namespace App\Http\Controllers;

use App\Http\Resources\Employee\CreateEmployeeResource;
use App\Http\Resources\Employee\DeleteEmployeeByIdResource;
use App\Http\Resources\Employee\GetAllEmployeesResource;
use App\Http\Resources\Employee\GetEmployeeByIdResource;
use App\Http\Resources\Employee\UpdateEmployeeResource;
use Illuminate\Http\Request;
use Src\Administration\Application\Employee\CreateEmployee;
use Src\Administration\Application\Employee\DeleteEmployeeById;
use Src\Administration\Application\Employee\Dtos\CreateEmployeeInputDto;
use Src\Administration\Application\Employee\Dtos\DeleteEmployeeByIdInputDto;
use Src\Administration\Application\Employee\Dtos\GetEmployeeByIdInputDto;
use Src\Administration\Application\Employee\Dtos\UpdateEmployeeInputDto;
use Src\Administration\Application\Employee\GetAllEmployees;
use Src\Administration\Application\Employee\GetEmployeeById;
use Src\Administration\Application\Employee\UpdateEmployee;

class EmployeeController extends Controller
{
    public function index(
        GetAllEmployees $getAllEmployees
    ) {
        $output = $getAllEmployees->execute();

        return new GetAllEmployeesResource($output);
    }

    public function store(
        Request $request,
        CreateEmployee $createEmployee
    ) {
        $inputDto = new CreateEmployeeInputDto(
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
        string $id,
        GetEmployeeById $getEmployeeById
    ) {
        $inputDto = new GetEmployeeByIdInputDto(
            $id,
        );

        $output = $getEmployeeById->execute($inputDto);

        return new GetEmployeeByIdResource($output);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
        string $id,
        UpdateEmployee $updateEmployee
    ) {
        $inputDto = new UpdateEmployeeInputDto(
            $id,
            $request->name,
            $request->email,
            $request->password,
            $request->type
        );

        $output = $updateEmployee->execute($inputDto);

        return new UpdateEmployeeResource($output);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        string $id,
        DeleteEmployeeById $deleteEmployeeById
    ) {
        $inputDto = new DeleteEmployeeByIdInputDto(
            $id,
        );

        $output = $deleteEmployeeById->execute($inputDto);

        return new DeleteEmployeeByIdResource($output);
    }
}
