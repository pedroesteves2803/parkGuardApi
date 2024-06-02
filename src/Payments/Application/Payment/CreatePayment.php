<?php

namespace Src\Payments\Application\Payment;

use Src\Payments\Application\Payment\Dtos\CreatePaymentInputDto;
use Src\Payments\Application\Payment\Dtos\CreatePaymentOutputDto;
use Src\Payments\Domain\Entities\Payment;
use Src\Payments\Domain\Repositories\IPaymentRepository;
use Src\Payments\Domain\ValueObjects\DateTime;
use Src\Payments\Domain\ValueObjects\PaymentMethod;
use Src\Payments\Domain\ValueObjects\Value;
use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Vehicle\Dtos\GetVehicleInputDto;
use Src\Vehicles\Application\Vehicle\GetVehicleById;
use Src\Vehicles\Domain\Entities\Vehicle;

final class CreatePayment
{
    public function __construct(
        readonly IPaymentRepository $paymentsRepository,
        readonly GetVehicleById $getVehicleById,
        readonly Notification $notification,
    ) {
    }

    public function execute(CreatePaymentInputDto $input): CreatePaymentOutputDto
    {
        try {
            $vehicle = $this->getVehicleById($input->vehicle_id);

            $payment = $this->paymentsRepository->create(
                new Payment(
                    null,
                    new Value($input->value),
                    new DateTime($input->dateTime),
                    new PaymentMethod($input->paymentMethod),
                    $vehicle
                )
            );

            return new CreatePaymentOutputDto($payment, $this->notification);
        } catch (\Exception $e) {
            $this->notification->addError([
                'context' => 'create_employee',
                'message' => $e->getMessage(),
            ]);

            return new CreatePaymentOutputDto(null, $this->notification);
        }
    }

    private function getVehicleById(string $vehicle_id): Vehicle
    {
        $getVehicleOutputDto = $this->getVehicleById->execute(
            new GetVehicleInputDto($vehicle_id)
        );

        if (is_null($getVehicleOutputDto->vehicle)) {
            throw new \Exception('Veículo não cadastrado!');
        }

        return $getVehicleOutputDto->vehicle;
    }
}
