<?php

namespace Src\Payments\Application\Usecase;

use Src\Payments\Application\Dtos\FinalizePaymentInputDto;
use Src\Payments\Application\Dtos\FinalizePaymentOutputDto;
use Src\Payments\Application\Dtos\GetPaymentByIdInputDto;
use Src\Payments\Domain\Repositories\IPaymentRepository;
use Src\Shared\Utils\Notification;

final readonly class FinalizePayment
{
    public function __construct(
        public IPaymentRepository $paymentsRepository,
        public GetPaymentById     $getPaymentById,
        public Notification       $notification,
    ) {
    }

    public function execute(FinalizePaymentInputDto $input): FinalizePaymentOutputDto
    {
        try {
            $getPaymentByIdOutputDto = $this->getPaymentById->execute(
                new GetPaymentByIdInputDto($input->id)
            );

            if($getPaymentByIdOutputDto->notification->hasErrors()){
                return new FinalizePaymentOutputDto(null, $getPaymentByIdOutputDto->notification);
            }

            $payment = $this->paymentsRepository->finalize(
                $getPaymentByIdOutputDto->payment
            );

            if (is_null($payment)) {
                $this->notification->addError([
                    'context' => 'finalize_payment',
                    'message' => 'Pagamento já foi finalizado!',
                ]);

                return new FinalizePaymentOutputDto(null, $this->notification);
            }

            return new FinalizePaymentOutputDto($payment, $this->notification);
        } catch (\Exception $e) {
            $this->notification->addError([
                'context' => 'finalize_payment',
                'message' => $e->getMessage(),
            ]);

            return new FinalizePaymentOutputDto(null, $this->notification);
        }
    }
}
