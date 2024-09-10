<?php

namespace Src\Payments\Application\Payment;

use Src\Payments\Application\Payment\Dtos\DeletePaymentInputDto;
use Src\Payments\Application\Payment\Dtos\DeletePaymentOutputDto;
use Src\Payments\Application\Payment\Dtos\GetPaymentByIdInputDto;
use Src\Payments\Domain\Repositories\IPaymentRepository;
use Src\Shared\Utils\Notification;

final readonly class DeletePaymentById
{
    public function __construct(
        public IPaymentRepository $paymentsRepository,
        public GetPaymentById     $getPaymentById,
        public Notification       $notification,
    ) {
    }

    public function execute(DeletePaymentInputDto $input): DeletePaymentOutputDto
    {
        try {
            $getPaymentByIdOutputDto = $this->getPaymentById->execute(
                new GetPaymentByIdInputDto($input->id)
            );

            if($getPaymentByIdOutputDto->notification->hasErrors()){
                return new DeletePaymentOutputDto(null, $getPaymentByIdOutputDto->notification);
            }

            $payment = $getPaymentByIdOutputDto->payment;

            if ($payment->paid()) {
                $this->notification->addError([
                    'context' => 'delete_payment',
                    'message' => 'Este pagamento não pode ser excluído porque já foi registrado como pago.',
                ]);

                return new DeletePaymentOutputDto(null, $this->notification);
            }

            $this->paymentsRepository->delete(
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

}
