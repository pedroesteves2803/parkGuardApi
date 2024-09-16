<?php

namespace Src\Payments\Application\Usecase;

use Src\Payments\Application\Dtos\DeletePaymentInputDto;
use Src\Payments\Application\Dtos\DeletePaymentOutputDto;
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

            $payment = $this->paymentsRepository->getById($input->id);

            if(is_null($payment)) {
                $this->notification->addError([
                    'context' => 'delete_payment',
                    'message' => 'Pagamento não registrado!',
                ]);

                return new DeletePaymentOutputDto(null, $this->notification);
            }

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
