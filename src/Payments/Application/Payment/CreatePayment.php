<?php

namespace Src\Payments\Application\Payment;

use Src\Payments\Application\Payment\Dtos\CreatePaymentInputDto;
use Src\Payments\Application\Payment\Dtos\CreatePaymentOutputDto;
use Src\Payments\Domain\Entities\Payment;
use Src\Payments\Domain\Repositories\IPaymentRepository;
use Src\Payments\Domain\ValueObjects\PaymentMethod;
use Src\Payments\Domain\ValueObjects\RegistrationTime;
use Src\Payments\Domain\ValueObjects\Value;
use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Vehicle\Dtos\ExitVehicleInputDto;
use Src\Vehicles\Application\Vehicle\Dtos\GetVehicleInputDto;
use Src\Vehicles\Application\Vehicle\ExitVehicle;
use Src\Vehicles\Application\Vehicle\GetVehicleById;
use Src\Vehicles\Domain\Entities\Vehicle;

final class CreatePayment
{
    private const VALUE_HOUR = 2000;

    private const MORE_THAN_AN_HOUR = 1000;

    public function __construct(
        readonly IPaymentRepository $paymentsRepository,
        readonly GetVehicleById $getVehicleById,
        readonly ExitVehicle $exitVehicle,
        readonly Notification $notification,
        readonly CalculateValue $calculateValue
    ) {
    }

    public function execute(CreatePaymentInputDto $input): CreatePaymentOutputDto
    {
        try {
            $vehicle = $this->getVehicleById($input->vehicle_id);

            $exitVehicle = $this->exitVehicle($vehicle);

            $calculateValue = $this->calculateValue->execute($exitVehicle);

            $payment = $this->paymentsRepository->create(
                new Payment(
                    null,
                    new Value($calculateValue->totalToPay),
                    new RegistrationTime($input->dateTime),
                    new PaymentMethod($input->paymentMethod),
                    false,
                    $vehicle
                )
            );

            return new CreatePaymentOutputDto($payment, $this->notification);
        } catch (\Exception $e) {
            $this->notification->addError([
                'context' => 'create_payment',
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
            throw new \RuntimeException('Veículo não cadastrado!');
        }

        return $getVehicleOutputDto->vehicle;
    }

    private function exitVehicle(Vehicle $vehicle): Vehicle
    {
        $exitVehicleOutputDto = $this->exitVehicle->execute(
            new ExitVehicleInputDto($vehicle->licensePlate()->value())
        );

        if (is_null($exitVehicleOutputDto->vehicle)) {
            throw new \RuntimeException('Veículo não cadastrado!');
        }

        return $exitVehicleOutputDto->vehicle;
    }
}
