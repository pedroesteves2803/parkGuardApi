<?php

namespace Src\Vehicle\Application\Employee;

use Src\Administration\Application\Employee\Dtos\CreateEmployeeInputDto;
use Src\Administration\Application\Employee\Dtos\CreateEmployeeOutputDto;
use Src\Administration\Application\Employee\Dtos\CreateVehicleInputDto;
use Src\Administration\Application\Employee\Dtos\CreateVehicleOutputDto;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\Repositories\IEmployeeRepository;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\Name;
use Src\Administration\Domain\ValueObjects\Password;
use Src\Administration\Domain\ValueObjects\Type;
use Src\Shared\Utils\Notification;

final class CreateVehicle
{
    public function __construct(
        readonly IEmployeeRepository $iEmployeeRepository,
        readonly Notification $notification,
    ) {
    }

    public function execute(CreateVehicleInputDto $input): CreateVehicleOutputDto
    {
        $existEmployee = $this->resolveExistVehicle($input->licensePlate);

        if ($existEmployee instanceof Notification) {
            return new CreateEmployeeOutputDto(
                null,
                $this->notification
            );
        }

        $employee = $this->iEmployeeRepository->create(
            new Employee(
                null,
                new Name($input->name),
                new Email($input->email),
                new Password($input->password),
                new Type($input->type),
            )
        );

        return new CreateEmployeeOutputDto(
            $employee,
            $this->notification
        );
    }

    private function resolveExistVehicle(string $licensePlate): bool|Notification
    {
        $existEmployee = $this->iEmployeeRepository->existByEmail(
            new Email($employeeEmail)
        );

        if ($existEmployee) {
            return $this->notification->addError([
                'context' => 'license_plate_already_exists',
                'message' => 'Placa já cadastrado!',
            ]);
        }

        return $existEmployee;
    }
}
