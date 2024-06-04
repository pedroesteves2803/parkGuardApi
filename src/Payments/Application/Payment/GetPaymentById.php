<?php

namespace Src\Payments\Application\Payment;

use Src\Payments\Application\Payment\Dtos\CreatePaymentInputDto;
use Src\Payments\Application\Payment\Dtos\CreatePaymentOutputDto;
use Src\Payments\Application\Payment\Dtos\DeletePaymentInputDto;
use Src\Payments\Application\Payment\Dtos\GetPaymentByIdInputDto;
use Src\Payments\Application\Payment\Dtos\GetPaymentByIdOutputDto;
use Src\Payments\Domain\Entities\Payment;
use Src\Payments\Domain\Repositories\IPaymentRepository;
use Src\Payments\Domain\ValueObjects\DateTime;
use Src\Payments\Domain\ValueObjects\PaymentMethod;
use Src\Payments\Domain\ValueObjects\Value;
use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Vehicle\Dtos\ExitVehicleInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\GetVehicleInputDto;
use Src\Vehicles\Application\Vehicle\ExitVehicle;
use Src\Vehicles\Application\Vehicle\GetVehicleById;
use Src\Vehicles\Domain\Entities\Vehicle;

final class GetPaymentById
{
    public function __construct(
        readonly IPaymentRepository $paymentsRepository,
        readonly Notification $notification,
    ) {
    }

    public function execute(GetPaymentByIdInputDto $input): GetPaymentByIdOutputDto
    {
        try {
            $payment = $this->getPaymentById($input->id);

            return new GetPaymentByIdOutputDto($payment, $this->notification);
        } catch (\Exception $e) {
            $this->notification->addError([
                'context' => 'get_payment_by_id',
                'message' => $e->getMessage(),
            ]);

            return new GetPaymentByIdOutputDto(null, $this->notification);
        }
    }

    private function getPaymentById(int $id): Payment
    {
        $payment = $this->paymentsRepository->getById(
            $id
        );

        if (is_null($payment)) {
            throw new \Exception('Pagamento não registrado!');
        }

        return $payment;
    }
}
