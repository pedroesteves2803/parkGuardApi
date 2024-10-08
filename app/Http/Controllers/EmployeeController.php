<?php

namespace App\Http\Controllers;

use App\Http\Requests\Employee\CreateEmployeeRequest;
use App\Http\Requests\employee\LoginEmployeeRequest;
use App\Http\Requests\employee\LogoutEmployeeRequest;
use App\Http\Requests\Employee\passwordResetRequest;
use App\Http\Requests\Employee\passwordResetTokenRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;
use App\Http\Requests\Employee\verifyTokenPasswordRequest;
use App\Http\Resources\Employee\CreateEmployeeResource;
use App\Http\Resources\Employee\DeleteEmployeeByIdResource;
use App\Http\Resources\Employee\GetAllEmployeesResource;
use App\Http\Resources\Employee\GetEmployeeByIdResource;
use App\Http\Resources\Employee\LoginEmployeeResource;
use App\Http\Resources\Employee\LogoutEmployeeResource;
use App\Http\Resources\Employee\PasswordResetResource;
use App\Http\Resources\Employee\PasswordResetTokenResource;
use App\Http\Resources\Employee\UpdateEmployeeResource;
use App\Http\Resources\Employee\VerifyTokenPasswordResetResource;
use Src\Administration\Application\Dtos\CreateEmployeeInputDto;
use Src\Administration\Application\Dtos\DeleteEmployeeByIdInputDto;
use Src\Administration\Application\Dtos\GeneratePasswordResetTokenEmployeeInputDto;
use Src\Administration\Application\Dtos\GetEmployeeByIdInputDto;
use Src\Administration\Application\Dtos\LoginEmployeeInputDto;
use Src\Administration\Application\Dtos\LogoutEmployeeInputDto;
use Src\Administration\Application\Dtos\PasswordResetEmployeeInputDto;
use Src\Administration\Application\Dtos\UpdateEmployeeInputDto;
use Src\Administration\Application\Dtos\VerifyTokenPasswordResetInputDto;
use Src\Administration\Application\Usecase\CreateEmployee;
use Src\Administration\Application\Usecase\DeleteEmployeeById;
use Src\Administration\Application\Usecase\GeneratePasswordResetTokenEmployee;
use Src\Administration\Application\Usecase\GetAllEmployees;
use Src\Administration\Application\Usecase\GetEmployeeById;
use Src\Administration\Application\Usecase\LoginEmployee;
use Src\Administration\Application\Usecase\LogoutEmployee;
use Src\Administration\Application\Usecase\ResetPasswordEmployee;
use Src\Administration\Application\Usecase\UpdateEmployee;
use Src\Administration\Application\Usecase\VerifyTokenPasswordReset;

/**
 * Class EmployeeController.
 *
 * @OA\Tag(
 *     name="Employee",
 *     description="Endpoints de funcionários"
 * )
 */
class EmployeeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/employees",
     *     summary="Get all employees",
     *     tags={"Employee"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="A list of employees",
     *
     *         @OA\JsonContent(ref="#/components/schemas/GetAllEmployeesResource")
     *     )
     * )
     */
    public function index(
        GetAllEmployees $getAllEmployees
    ): GetAllEmployeesResource
    {
        $output = $getAllEmployees->execute();

        return new GetAllEmployeesResource($output);
    }

    /**
     * @OA\Post(
     *     path="/api/employee",
     *     summary="Create employee",
     *     tags={"Employee"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 description="Name of the employee"
     *             ),
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 format="email",
     *                 description="Email address of the employee"
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 description="Password for the employee account"
     *             ),
     *             @OA\Property(
     *                 property="type",
     *                 type="integer",
     *                 description="Type of employee"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Employees",
     *
     *         @OA\JsonContent(ref="#/components/schemas/CreateEmployeeResource")
     *     )
     * )
     */
    public function store(
        CreateEmployeeRequest $request,
        CreateEmployee $createEmployee
    ): CreateEmployeeResource
    {

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

    /**
     * @OA\Get(
     *     path="/api/employee/{id}",
     *     summary="Get employee by ID",
     *     tags={"Employee"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the employee to retrieve",
     *
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Employee details",
     *
     *         @OA\JsonContent(ref="#/components/schemas/GetEmployeeByIdResource")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Employee not found"
     *     )
     * )
     */
    public function show(
        string $id,
        GetEmployeeById $getEmployeeById
    ): GetEmployeeByIdResource
    {
        $inputDto = new GetEmployeeByIdInputDto(
            $id,
        );

        $output = $getEmployeeById->execute($inputDto);

        return new GetEmployeeByIdResource($output);
    }

    /**
     * @OA\Put(
     *     path="/api/employee/{id}",
     *     summary="Update employee",
     *     tags={"Employee"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the employee to update",
     *
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 description="Name of the employee"
     *             ),
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 format="email",
     *                 description="Email address of the employee"
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 description="Password for the employee account"
     *             ),
     *             @OA\Property(
     *                 property="type",
     *                 type="string",
     *                 description="Type of employee"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Updated employee details",
     *
     *         @OA\JsonContent(ref="#/components/schemas/UpdateEmployeeResource")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Employee not found"
     *     )
     * )
     */
    public function update(
        UpdateEmployeeRequest $request,
        string $id,
        UpdateEmployee $updateEmployee
    ): UpdateEmployeeResource
    {
        $inputDto = new UpdateEmployeeInputDto(
            $id,
            $request->name,
            $request->email,
            $request->type
        );

        $output = $updateEmployee->execute($inputDto);

        return new UpdateEmployeeResource($output);
    }

    /**
     * @OA\Delete(
     *     path="/api/employee/{id}",
     *     summary="Delete employee",
     *     tags={"Employee"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the employee to delete",
     *
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Deleted employee details",
     *
     *         @OA\JsonContent(ref="#/components/schemas/DeleteEmployeeByIdResource")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Employee not found"
     *     )
     * )
     */
    public function destroy(
        string $id,
        DeleteEmployeeById $deleteEmployeeById
    ): DeleteEmployeeByIdResource
    {
        $inputDto = new DeleteEmployeeByIdInputDto(
            $id,
        );

        $output = $deleteEmployeeById->execute($inputDto);

        return new DeleteEmployeeByIdResource($output);
    }

    /**
     * @OA\Post(
     *     path="/api/employee/login",
     *     summary="Login employee",
     *     tags={"Employee"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 format="email",
     *                 description="Email address of the employee"
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 description="Password for the employee account"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Logged in employee details",
     *
     *         @OA\JsonContent(ref="#/components/schemas/LoginEmployeeResource")
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials"
     *     )
     * )
     */
    public function login(
        LoginEmployeeRequest $request,
        LoginEmployee $loginEmployee
    ): LoginEmployeeResource
    {
        $inputDto = new LoginEmployeeInputDto(
            $request->email,
            $request->password,
        );

        $outputDto = $loginEmployee->execute($inputDto);

        return new LoginEmployeeResource($outputDto);
    }

    /**
     * @OA\Post(
     *     path="/api/employee/logout",
     *     summary="Logout employee",
     *     tags={"Employee"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="token",
     *                 type="string",
     *                 description="JWT token of the employee"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Logged out successfully"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *
     *     )
     * )
     */
    public function logout(
        LogoutEmployeeRequest $request,
        LogoutEmployee $logoutEmployee
    ): LogoutEmployeeResource
    {
        $inputDto = new LogoutEmployeeInputDto(
            $request->token
        );

        $outputDto = $logoutEmployee->execute($inputDto);

        return new LogoutEmployeeResource($outputDto);
    }

    public function passwordResetToken(
        passwordResetTokenRequest $request,
        GeneratePasswordResetTokenEmployee $generatePasswordResetTokenEmployee
    ): PasswordResetTokenResource
    {
        $inputDto = new GeneratePasswordResetTokenEmployeeInputDto(
            $request->email,
        );

        $outputDto = $generatePasswordResetTokenEmployee->execute($inputDto);

        return new PasswordResetTokenResource($outputDto);
    }

    public function verifyTokenPasswordReset(
        verifyTokenPasswordRequest $request,
        VerifyTokenPasswordReset $verifyTokenPasswordReset
    ): VerifyTokenPasswordResetResource
    {

        $inputDto = new VerifyTokenPasswordResetInputDto(
            $request->code,
        );

        $outputDto = $verifyTokenPasswordReset->execute($inputDto);

        return new VerifyTokenPasswordResetResource($outputDto);
    }

    public function passwordReset(
        passwordResetRequest $request,
        ResetPasswordEmployee $resetPasswordEmployee
    ): PasswordResetResource
    {
        $inputDto = new PasswordResetEmployeeInputDto(
            $request->password,
            $request->code,
        );

        $outputDto = $resetPasswordEmployee->execute($inputDto);

        return new PasswordResetResource($outputDto);
    }
}
