<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment\CreatePaymentRequest;
use App\Http\Resources\Payment\CreatePaymentResource;
use App\Http\Resources\Payment\DeletePaymentByIdResource;
use App\Http\Resources\Payment\FinalizePaymentResource;
use App\Http\Resources\Payment\GetAllPaymentsResource;
use App\Http\Resources\Payment\GetPaymentByIdResource;
use DateTime;
use Src\Payments\Application\Dtos\CreatePaymentInputDto;
use Src\Payments\Application\Dtos\DeletePaymentInputDto;
use Src\Payments\Application\Dtos\FinalizePaymentInputDto;
use Src\Payments\Application\Dtos\GetPaymentByIdInputDto;
use Src\Payments\Application\Usecase\CreatePayment;
use Src\Payments\Application\Usecase\DeletePaymentById;
use Src\Payments\Application\Usecase\FinalizePayment;
use Src\Payments\Application\Usecase\GetAllPayments;
use Src\Payments\Application\Usecase\GetPaymentById;

/**
 * Class PaymentController.
 *
 * @OA\Tag(
 *     name="Payment",
 *     description="Endpoints de pagamentos"
 * )
 */
class PaymentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/payments",
     *     summary="Get all payments",
     *     tags={"Payment"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="A list of payments",
     *         @OA\JsonContent(ref="#/components/schemas/GetAllPaymentsResource")
     *     )
     * )
     */
    public function index(
        GetAllPayments $getAllPayments
    ): GetAllPaymentsResource
    {
        $output = $getAllPayments->execute();

        return new GetAllPaymentsResource($output);
    }

    /**
     * @OA\Post(
     *     path="/api/payment",
     *     summary="Create payment",
     *     tags={"Payment"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="paymentMethod",
     *                 type="string",
     *                 description="Method of the payment"
     *             ),
     *             @OA\Property(
     *                 property="vehicle_id",
     *                 type="integer",
     *                 description="ID of the vehicle associated with the payment"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment created",
     *         @OA\JsonContent(ref="#/components/schemas/CreatePaymentResource")
     *     )
     * )
     */
    public function store(
        CreatePaymentRequest $request,
        CreatePayment $createPayment
    ): CreatePaymentResource
    {
        $createPaymentInputDto = new CreatePaymentInputDto(
            new DateTime(),
            $request->paymentMethod,
            $request->vehicleId
        );

        $createOutputDto = $createPayment->execute($createPaymentInputDto);

        return new CreatePaymentResource($createOutputDto);
    }

    /**
     * @OA\Get(
     *     path="/api/payment/{id}",
     *     summary="Get payment by ID",
     *     tags={"Payment"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the payment to retrieve",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment details",
     *         @OA\JsonContent(ref="#/components/schemas/GetPaymentByIdResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Payment not found"
     *     )
     * )
     */
    public function show(
        int $id,
        GetPaymentById $getPaymentById
    ): GetPaymentByIdResource
    {
        $getPaymentByIdInputDto = new GetPaymentByIdInputDto($id);

        $getPaymentByIdOutputDto = $getPaymentById->execute($getPaymentByIdInputDto);

        return new GetPaymentByIdResource($getPaymentByIdOutputDto);
    }

    /**
     * @OA\Post(
     *     path="/api/payment/{id}/finalize",
     *     summary="Finalize payment",
     *     tags={"Payment"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the payment to finalize",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment finalized",
     *         @OA\JsonContent(ref="#/components/schemas/FinalizePaymentResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Payment not found"
     *     )
     * )
     */
    public function finalize(
        int $id,
        FinalizePayment $finalizePayment
    ): FinalizePaymentResource
    {
        $finalizePaymentInputDto = new FinalizePaymentInputDto($id);

        $finalizePaymentOutputDto = $finalizePayment->execute($finalizePaymentInputDto);

        return new FinalizePaymentResource($finalizePaymentOutputDto);
    }

    /**
     * @OA\Delete(
     *     path="/api/payment/{id}",
     *     summary="Delete payment by ID",
     *     tags={"Payment"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the payment to delete",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment deleted",
     *         @OA\JsonContent(ref="#/components/schemas/DeletePaymentByIdResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Payment not found"
     *     )
     * )
     */
    public function destroy(
        int $id,
        DeletePaymentById $deletePaymentById
    ): DeletePaymentByIdResource
    {
        $deletePaymentInputDto = new DeletePaymentInputDto($id);

        $deletePaymentOutputDto = $deletePaymentById->execute($deletePaymentInputDto);

        return new DeletePaymentByIdResource($deletePaymentOutputDto);
    }
}
