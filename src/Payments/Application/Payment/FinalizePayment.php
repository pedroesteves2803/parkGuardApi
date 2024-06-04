<?php

namespace Src\Payments\Application\Payment;

use Src\Payments\Application\Payment\Dtos\FinalizePaymentInputDto;
use Src\Payments\Application\Payment\Dtos\FinalizePaymentOutputDto;
use Src\Payments\Application\Payment\Dtos\GetPaymentByIdInputDto;
use Src\Payments\Domain\Entities\Payment;
use Src\Payments\Domain\Repositories\IPaymentRepository;
use Src\Shared\Utils\Notification;

final class FinalizePayment
{
    public function __construct(
        readonly IPaymentRepository $paymentsRepository,
        readonly GetPaymentById $getPaymentById,
        readonly Notification $notification,
    ) {
    }

    public function execute(FinalizePaymentInputDto $input): FinalizePaymentOutputDto
    {
        try {
            $payment = $this->getPaymentById($input->id);

            $payment = $this->finalizePayment($payment);

            return new FinalizePaymentOutputDto($payment, $this->notification);
        } catch (\Exception $e) {
            $this->notification->addError([
                'context' => 'finalize_payment',
                'message' => $e->getMessage(),
            ]);

            return new FinalizePaymentOutputDto(null, $this->notification);
        }
    }

    private function getPaymentById(int $id): Payment
    {
        $getPaymentByIdOutputDto = $this->getPaymentById->execute(
            new GetPaymentByIdInputDto($id)
        );

        if (is_null($getPaymentByIdOutputDto->payment)) {
            throw new \Exception('Pagamento não cadastrado!');
        }

        return $getPaymentByIdOutputDto->payment;
    }

    private function finalizePayment(Payment $payment): Payment
    {
        $payment = $this->paymentsRepository->finalize(
            $payment
        );

        if (is_null($payment)) {
            throw new \Exception('Pagamento não ja foi finalizado!');
        }

        return $payment;
    }
}
