<?php

use Src\Administration\Application\Employee\CreateEmployee;
use Src\Administration\Application\Employee\Dtos\CreateEmployeeInputDto;
use Src\Administration\Application\Employee\Dtos\CreateEmployeeOutputDto;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\Name;
use Src\Administration\Domain\ValueObjects\Password;
use Src\Administration\Domain\ValueObjects\Type;
use Src\Payments\Application\Payment\CreatePayment;
use Src\Payments\Application\Payment\Dtos\CreatePaymentInputDto;
use Src\Payments\Application\Payment\Dtos\CreatePaymentOutputDto;
use Src\Payments\Domain\Entities\Payment;
use Src\Payments\Domain\Repositories\IPaymentRepository;
use Src\Shared\Utils\Notification;
use Src\Vehicles\Application\Vehicle\ExitVehicle;
use Src\Vehicles\Application\Vehicle\GetVehicleById;
use Src\Vehicles\Domain\Entities\Vehicle;
use Src\Vehicles\Domain\Repositories\IVehicleRepository;

beforeEach(function () {
    $this->notification = new Notification();
    $this->IVehicleRepositoryMock = mock(IVehicleRepository::class);
    $this->repositoryMock = mock(IPaymentRepository::class);
    $this->getVehicleById = new GetVehicleById($this->IVehicleRepositoryMock, $this->notification);
    $this->exitVehicle = new ExitVehicle($this->IVehicleRepositoryMock, $this->notification);
});

it('successfully creates an payment', function () {

    $this->repositoryMock->shouldReceive('create')->once()->andReturn(
        mock(Payment::class)
    );

    $this->IVehicleRepositoryMock->shouldReceive('getById')->andReturn(mock(Vehicle::class));

    $this->IVehicleRepositoryMock->shouldReceive('licensePlate')->andReturn(mock(Vehicle::class));


    $createEmployee = new CreatePayment(
        $this->repositoryMock,
        $this->getVehicleById,
        $this->exitVehicle,
        $this->notification
    );

    $inputDto = new CreatePaymentInputDto(
        now(),
        1,
        1
    );

    $outputDto = $createEmployee->execute($inputDto);

    print_r($outputDto);

    expect($outputDto)->toBeInstanceOf(CreatePaymentOutputDto::class);
    expect($outputDto->payment)->toBeInstanceOf(Payment::class);
    expect($outputDto->notification->getErrors())->toBeEmpty();
});

// it('fails to create an employee with existing email', function () {
//     $notification = new Notification();

//     $this->repositoryMock->shouldReceive('existByEmail')->once()->andReturnTrue();
//     $createEmployee = new CreateEmployee($this->repositoryMock, $notification);

//     $inputDto = new CreateEmployeeInputDto('Nome', 'email@test.com', 'Password@123', 1);
//     $outputDto = $createEmployee->execute($inputDto);

//     expect($outputDto)->toBeInstanceOf(CreateEmployeeOutputDto::class);
//     expect($outputDto->employee)->toBeNull();
//     expect($outputDto->notification->getErrors())->toBe([
//         [
//             'context' => 'create_employee',
//             'message' => 'Email já cadastrado!',
//         ],
//     ]);
// });
