<?php

namespace Src\Payments\Application\Payment;

use Src\Payments\Application\Payment\Dtos\DeletePaymentInputDto;
use Src\Payments\Application\Payment\Dtos\DeletePaymentOutputDto;
use Src\Payments\Application\Payment\Dtos\GetPaymentByIdInputDto;
use Src\Payments\Domain\Entities\Payment;
use Src\Payments\Domain\Repositories\IPaymentRepository;
use Src\Shared\Utils\Notification;

final class DeletePaymentById
{
    public function __construct(
        readonly IPaymentRepository $paymentsRepository,
        readonly GetPaymentById $getPaymentById,
        readonly Notification $notification,
    ) {
    }

    public function execute(DeletePaymentInputDto $input): DeletePaymentOutputDto
    {
        try {
            $payment = $this->getPaymentById($input->id);

            $this->checkIfItHasAlreadyBeenPaid($payment);

            $payment = $this->paymentsRepository->delete(
                $payment->id()
            );

            return new DeletePaymentOutputDto(null, $this->notification);
        } catch (\Exception $e) {
            $this->notification->addError([
                'context' => 'delete_payment',
                'message' => $e->getMessage(),
            ]);

            return new DeletePaymentOutputDto(null, $this->notification);
        }
    }

    private function getPaymentById(int $id): Payment
    {
        $getPaymentByIdOutputDto = $this->getPaymentById->execute(
            new GetPaymentByIdInputDto($id)
        );

        if (is_null($getPaymentByIdOutputDto->payment)) {
            throw new \Exception('Pagamento não registrado!');
        }

        return $getPaymentByIdOutputDto->payment;
    }

    private function checkIfItHasAlreadyBeenPaid(Payment $payment): void
    {
        if ($payment->paid()) {
            throw new \Exception('Este pagamento não pode ser excluído porque já foi registrado como pago.');
        }
    }
}
