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

final readonly class CreatePayment
{
    public function __construct(
        public IPaymentRepository $paymentsRepository,
        public GetVehicleById     $getVehicleById,
        public ExitVehicle        $exitVehicle,
        public Notification       $notification,
        public CalculateValue     $calculateValue
    ) {
    }

    public function execute(CreatePaymentInputDto $input): CreatePaymentOutputDto
    {
        try {
            $getVehicleOutputDto = $this->getVehicleById->execute(
                new GetVehicleInputDto($input->vehicle_id)
            );

            if($getVehicleOutputDto->notification->hasErrors()){
                return new CreatePaymentOutputDto(null, $getVehicleOutputDto->notification);
            }

            $exitVehicleOutputDto = $this->exitVehicle->execute(
                new ExitVehicleInputDto($getVehicleOutputDto->vehicle->licensePlate()->value())
            );

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
