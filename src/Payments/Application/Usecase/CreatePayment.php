<?php

namespace Src\Payments\Application\Usecase;

use Src\Payments\Application\Dtos\CreatePaymentInputDto;
use Src\Payments\Application\Dtos\CreatePaymentOutputDto;
use Src\Payments\Domain\Entities\Payment;
use Src\Payments\Domain\Repositories\IPaymentRepository;
use Src\Payments\Domain\ValueObjects\PaymentMethod;
use Src\Payments\Domain\ValueObjects\RegistrationTime;
use Src\Payments\Domain\ValueObjects\Value;
use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Dtos\ExitVehicleInputDto;
use Src\Vehicles\Application\Dtos\GetVehicleInputDto;
use Src\Vehicles\Application\Usecase\ExitVehicle;
use Src\Vehicles\Application\Usecase\GetVehicleById;
use Src\Vehicles\Domain\Service\IVehicleService;

final readonly class CreatePayment
{
    public function __construct(
        private IPaymentRepository $paymentsRepository,
        private Notification       $notification,
        private CalculateValue     $calculateValue,
        private IVehicleService   $vehicleService,
    ) {
    }

    public function execute(CreatePaymentInputDto $input): CreatePaymentOutputDto
    {
        try {

            $getVehicleOutputDto = $this->vehicleService->getVehicleById($input->vehicle_id);

            if($getVehicleOutputDto->notification->hasErrors()){
                return new CreatePaymentOutputDto(null, $getVehicleOutputDto->notification);
            }

            $exitVehicleOutputDto = $this->vehicleService->exitVehicle($getVehicleOutputDto->vehicle->licensePlate()->value());

            if($exitVehicleOutputDto->notification->hasErrors()){
                return new CreatePaymentOutputDto(null, $exitVehicleOutputDto->notification);
            }

            $calculateValue = $this->calculateValue->execute($exitVehicleOutputDto->vehicle);

            if(is_null($calculateValue->totalToPay)){
                return new CreatePaymentOutputDto(null, $calculateValue->notification);
            }

            $payment = $this->paymentsRepository->create(
                new Payment(
                    null,
                    new Value($calculateValue->totalToPay),
                    new RegistrationTime($input->dateTime),
                    new PaymentMethod($input->paymentMethod),
                    false,
                    $exitVehicleOutputDto->vehicle
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
}
