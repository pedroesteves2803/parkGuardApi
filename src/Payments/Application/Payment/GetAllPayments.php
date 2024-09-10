<?php

namespace Src\Payments\Application\Payment;

use Illuminate\Support\Collection;
use Src\Payments\Application\Payment\Dtos\GetAllPaymentsOutputDto;
use Src\Payments\Domain\Repositories\IPaymentRepository;
use Src\Shared\Utils\Notification;

final readonly class GetAllPayments
{
    public function __construct(
        public IPaymentRepository $paymentsRepository,
        public Notification       $notification,
    ) {
    }

    public function execute(): GetAllPaymentsOutputDto
    {
        try {
            $payments = $this->paymentsRepository->getAll();

            if (is_null($payments)) {
                $this->notification->addError([
                    'context' => 'get_all_payments',
                    'message' => 'Não possui pagamentos!',
                ]);

                return new GetAllPaymentsOutputDto(null, $this->notification);
            }

            return new GetAllPaymentsOutputDto($payments, $this->notification);
        } catch (\Exception $e) {
            $this->notification->addError([
                'context' => 'get_all_payments',
                'message' => $e->getMessage(),
            ]);

            return new GetAllPaymentsOutputDto(null, $this->notification);
        }
    }
}
