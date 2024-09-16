<?php

namespace Src\Payments\Application\Usecase;

use DateTime;
use Src\Payments\Application\Dtos\CreatePaymentInputDto;
use Src\Payments\Application\Dtos\CreatePaymentOutputDto;
use Src\Payments\Domain\Entities\Payment;
use Src\Payments\Domain\Factory\PaymentFactory;
use Src\Payments\Domain\Repositories\IPaymentRepository;
use Src\Payments\Domain\ValueObjects\PaymentMethod;
use Src\Payments\Domain\ValueObjects\RegistrationTime;
use Src\Payments\Domain\ValueObjects\Value;
use Src\Shared\Utils\Notification;
use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\Service\IVehicleService;

final readonly class CreatePayment
{
    public function __construct(
        private IPaymentRepository $paymentsRepository,
        private Notification $notification,
        private IVehicleService $vehicleService,
        private PaymentFactory $paymentFactory
    ) {}

    public function execute(CreatePaymentInputDto $input): CreatePaymentOutputDto
    {
        try {
            $getVehicleOutputDto = $this->vehicleService->getVehicleById($input->vehicle_id);

            if ($getVehicleOutputDto->notification->hasErrors()) {
                return new CreatePaymentOutputDto(null, $getVehicleOutputDto->notification);
            }

            $exitVehicleOutputDto = $this->vehicleService->exitVehicle($getVehicleOutputDto->vehicle->licensePlate()->value());

            if ($exitVehicleOutputDto->notification->hasErrors()) {
                return new CreatePaymentOutputDto(null, $exitVehicleOutputDto->notification);
            }

            $payment = $this->createEntityPayment($input->dateTime, $input->paymentMethod, $exitVehicleOutputDto->vehicle);

            //verificar se esta correto
            $payment->calculateTotalToPay();

            if ($payment->getNotification()->hasErrors()) {
                return new CreatePaymentOutputDto(null, $payment->getNotification());
            }

            $this->paymentsRepository->create($payment);

            return new CreatePaymentOutputDto($payment, $this->notification);
        } catch (\Exception $e) {
            $this->notification->addError([
                'context' => 'create_payment',
                'message' => $e->getMessage(),
            ]);

            return new CreatePaymentOutputDto(null, $this->notification);
        }
    }

    private function createEntityPayment(DateTime $registrationTime, string $paymentMethod, Vehicle $vehicle): Payment
    {
        return $this->paymentFactory->createWithCalculation(
            null,
            $registrationTime,
            $paymentMethod,
            false,
            $vehicle,
        );
    }
}
