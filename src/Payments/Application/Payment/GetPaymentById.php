<?php

namespace Src\Payments\Application\Payment;

use Src\Payments\Application\Payment\Dtos\GetPaymentByIdInputDto;
use Src\Payments\Application\Payment\Dtos\GetPaymentByIdOutputDto;
use Src\Payments\Domain\Entities\Payment;
use Src\Payments\Domain\Repositories\IPaymentRepository;
use Src\Shared\Utils\Notification;

final readonly class GetPaymentById
{
    public function __construct(
        public IPaymentRepository $paymentsRepository,
        public Notification       $notification,
    ) {
    }

    public function execute(GetPaymentByIdInputDto $input): GetPaymentByIdOutputDto
    {
        try {
            $payment = $this->paymentsRepository->getById(
                $input->id
            );

            if (is_null($payment)) {
                $this->notification->addError([
                    'context' => 'get_payment_by_id',
                    'message' => 'Pagamento não registrado!',
                ]);

                return new GetPaymentByIdOutputDto(null, $this->notification);
            }

            return new GetPaymentByIdOutputDto($payment, $this->notification);
        } catch (\Exception $e) {
            $this->notification->addError([
                'context' => 'get_payment_by_id',
                'message' => $e->getMessage(),
            ]);

            return new GetPaymentByIdOutputDto(null, $this->notification);
        }
    }

}
