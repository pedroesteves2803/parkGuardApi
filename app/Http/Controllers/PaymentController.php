<?php

namespace App\Http\Controllers;

use App\Http\Resources\Payment\CreatePaymentResource;
use App\Models\Payment;
use DateTime;
use Illuminate\Http\Request;
use Src\Payments\Application\Payment\CreatePayment;
use Src\Payments\Application\Payment\Dtos\CreatePaymentInputDto;

class PaymentController extends Controller
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
        CreatePayment $createPayment
    )
    {
        $createPaymentInputDto = new CreatePaymentInputDto(
            new DateTime(),
            $request->paymentMethod,
            $request->vehicle_id,
        );

        $createOutputDto = $createPayment->execute($createPaymentInputDto);

        return new CreatePaymentResource(
            $createOutputDto
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
