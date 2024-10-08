<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vehicle\CreateVehicleRequest;
use App\Http\Requests\vehicle\ExitVehicleRequest;
use App\Http\Requests\Vehicle\UpdateVehicleRequest;
use App\Http\Resources\Vehicle\CreateVehicleResource;
use App\Http\Resources\Vehicle\ExitVehicleResource;
use App\Http\Resources\Vehicle\GetAllVehiclesResource;
use App\Http\Resources\Vehicle\GetVehicleByIdResource;
use App\Http\Resources\Vehicle\UpdateVehicleResource;
use Src\Vehicles\Application\Dtos\CreateVehicleInputDto;
use Src\Vehicles\Application\Dtos\ExitVehicleInputDto;
use Src\Vehicles\Application\Dtos\GetVehicleInputDto;
use Src\Vehicles\Application\Dtos\UpdateVehicleInputDto;
use Src\Vehicles\Application\Usecase\CreateVehicle;
use Src\Vehicles\Application\Usecase\ExitVehicle;
use Src\Vehicles\Application\Usecase\GetAllVehicles;
use Src\Vehicles\Application\Usecase\GetVehicleById;
use Src\Vehicles\Application\Usecase\UpdateVehicle;
use Src\Vehicles\Domain\ValueObjects\LicensePlate;

/**
 * Class VehicleController
 *
 * @OA\Tag(
 *     name="Vehicle",
 *     description="Endpoints relacionados a veículos"
 * )
 */
class VehicleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/vehicles",
     *     summary="Lista todos os veículos",
     *     tags={"Vehicle"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Lista de veículos",
     *
     *         @OA\JsonContent(ref="#/components/schemas/GetAllVehiclesResource")
     *     )
     * )
     */
    public function index(
        GetAllVehicles $getAllVehicles
    ): GetAllVehiclesResource
    {
        $output = $getAllVehicles->execute();

        return new GetAllVehiclesResource($output);
    }

    /**
     * @OA\Post(
     *     path="/api/vehicle",
     *     summary="Cria um novo veículo",
     *     tags={"Vehicle"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="licensePlate",
     *                 type="string",
     *                 description="Placa do veículo"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Veículo criado",
     *
     *         @OA\JsonContent(ref="#/components/schemas/CreateVehicleResource")
     *     )
     * )
     */
    public function store(
        CreateVehicleRequest $request,
        CreateVehicle $createVehicle
    ): CreateVehicleResource
    {
        $createInputDto = new CreateVehicleInputDto(
            $request->licensePlate
        );

        $createOutputDto = $createVehicle->execute($createInputDto);

        return new CreateVehicleResource($createOutputDto);
    }

    /**
     * @OA\Get(
     *     path="/api/vehicle/{id}",
     *     summary="Obtém um veículo por ID",
     *     tags={"Vehicle"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do veículo",
     *
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes do veículo",
     *
     *         @OA\JsonContent(ref="#/components/schemas/GetVehicleByIdResource")
     *     )
     * )
     */
    public function show(
        int $id,
        GetVehicleById $getVehicleById
    ): GetVehicleByIdResource
    {
        $inputDto = new GetVehicleInputDto($id);

        $outputDto = $getVehicleById->execute($inputDto);

        return new GetVehicleByIdResource($outputDto);
    }

    /**
     * @OA\Put(
     *     path="/api/vehicle/{id}",
     *     summary="Atualiza os detalhes de um veículo",
     *     tags={"Vehicle"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do veículo",
     *
     *         @OA\Schema(
     *             type="integer"
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
     *                 property="manufacturer",
     *                 type="string",
     *                 description="Fabricante do veículo"
     *             ),
     *             @OA\Property(
     *                 property="color",
     *                 type="string",
     *                 description="Cor do veículo"
     *             ),
     *             @OA\Property(
     *                 property="model",
     *                 type="string",
     *                 description="Modelo do veículo"
     *             ),
     *             @OA\Property(
     *                 property="licensePlate",
     *                 type="string",
     *                 description="Placa do veículo"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Veículo atualizado",
     *
     *         @OA\JsonContent(ref="#/components/schemas/UpdateVehicleResource")
     *     )
     * )
     */
    public function update(
        UpdateVehicleRequest $request,
        int $id,
        UpdateVehicle $updateVehicle
    ): UpdateVehicleResource
    {

        $inputDto = new UpdateVehicleInputDto(
            $id,
            $request->manufacturer ?? null,
            $request->color ?? null,
            $request->model ?? null,
            $request->licensePlate
        );

        $outputDto = $updateVehicle->execute($inputDto);

        return new UpdateVehicleResource($outputDto);
    }

    /**
     * @OA\Post(
     *     path="/api/vehicle/exit",
     *     summary="Registra a saída de um veículo",
     *     tags={"Vehicle"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="licensePlate",
     *                 type="string",
     *                 description="Placa do veículo"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Saída do veículo registrada com sucesso",
     *
     *         @OA\JsonContent(ref="#/components/schemas/ExitVehicleResource")
     *     )
     * )
     */
    public function exit(
        ExitVehicleRequest $request,
        ExitVehicle $exitVehicle
    ): ExitVehicleResource
    {
        $inputDto = new ExitVehicleInputDto(
            new LicensePlate($request->licensePlate)
        );

        $outputDto = $exitVehicle->execute($inputDto);

        return new ExitVehicleResource($outputDto);
    }
}
