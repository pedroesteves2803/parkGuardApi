<?php

namespace App\Http\Controllers;

use App\Http\Resources\Payment\CreatePaymentResource;
use App\Http\Resources\Payment\DeletePaymentByIdResource;
use App\Http\Resources\Payment\FinalizePaymentResource;
use App\Http\Resources\Payment\GetAllPaymentsResource;
use App\Http\Resources\Payment\GetPaymentByIdResource;
use DateTime;
use Illuminate\Http\Request;
use Src\Payments\Application\Payment\CreatePayment;
use Src\Payments\Application\Payment\DeletePaymentById;
use Src\Payments\Application\Payment\Dtos\CreatePaymentInputDto;
use Src\Payments\Application\Payment\Dtos\DeletePaymentInputDto;
use Src\Payments\Application\Payment\Dtos\FinalizePaymentInputDto;
use Src\Payments\Application\Payment\Dtos\GetPaymentByIdInputDto;
use Src\Payments\Application\Payment\FinalizePayment;
use Src\Payments\Application\Payment\GetAllPayment;
use Src\Payments\Application\Payment\GetPaymentById;

class PaymentController extends Controller
{
    public function index(
        GetAllPayment $getAllPayment
    ) {
        $output = $getAllPayment->execute();

        return new GetAllPaymentsResource($output);
    }

    public function store(
        Request $request,
        CreatePayment $createPayment
    ) {
        $createPaymentInputDto = new CreatePaymentInputDto(
            new DateTime(),
            $request->paymentMethod,
            $request->vehicle_id,
        );

        $createOutputDto = $createPayment->execute($createPaymentInputDto);

        return new CreatePaymentResource(
            $createOutputDto,
        );
    }

    public function show(
        int $id,
        GetPaymentById $getPaymentById
    ) {
        $getPaymentByIdInputDto = new GetPaymentByIdInputDto(
            $id
        );

        $getPaymentByIdOutputDto = $getPaymentById->execute($getPaymentByIdInputDto);

        return new GetPaymentByIdResource(
            $getPaymentByIdOutputDto,
        );
    }

    public function finalize(
        int $id,
        FinalizePayment $finalizePayment
    ) {
        $finalizePaymentInputDto = new FinalizePaymentInputDto(
            $id
        );

        $finalizePaymentOutputDto = $finalizePayment->execute($finalizePaymentInputDto);

        return new FinalizePaymentResource(
            $finalizePaymentOutputDto,
        );
    }

    public function destroy(
        int $id,
        DeletePaymentById $deletePaymentById
    ) {
        $deletePaymentInputDto = new DeletePaymentInputDto(
            $id
        );

        $deletePaymentOutputDto = $deletePaymentById->execute($deletePaymentInputDto);

        return new DeletePaymentByIdResource(
            $deletePaymentOutputDto,
        );
    }
}
