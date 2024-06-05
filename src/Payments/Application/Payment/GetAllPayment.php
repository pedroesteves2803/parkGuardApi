<?php

namespace Src\Payments\Application\Payment;

use Illuminate\Support\Collection;
use Src\Payments\Application\Payment\Dtos\GetAllPaymentsOutputDto;
use Src\Payments\Domain\Repositories\IPaymentRepository;
use Src\Shared\Utils\Notification;

final class GetAllPayment
{
    public function __construct(
        readonly IPaymentRepository $paymentsRepository,
        readonly Notification $notification,
    ) {
    }

    public function execute(): GetAllPaymentsOutputDto
    {
        try {
            $payments = $this->getPayments();

            return new GetAllPaymentsOutputDto($payments, $this->notification);
        } catch (\Exception $e) {
            $this->notification->addError([
                'context' => 'get_all_payments',
                'message' => $e->getMessage(),
            ]);

            return new GetAllPaymentsOutputDto(null, $this->notification);
        }
    }

    private function getPayments(): Collection
    {
        $payments = $this->paymentsRepository->getAll();

        if (is_null($payments)) {
            throw new \Exception('Não possui pagamentos!');
        }

        return $payments;
    }
}