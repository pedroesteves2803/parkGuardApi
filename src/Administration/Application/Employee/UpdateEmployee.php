<?php

namespace Src\Administration\Application\Employee;

use Src\Administration\Application\Employee\Dtos\UpdateEmployeeInputDto;
use Src\Administration\Application\Employee\Dtos\UpdateEmployeeOutputDto;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\Repositories\IEmployeeRepository;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\Name;
use Src\Administration\Domain\ValueObjects\Type;
use Src\Shared\Utils\Notification;

final readonly class UpdateEmployee
{
    public function __construct(
        public IEmployeeRepository $iEmployeeRepository,
        public Notification        $notification,
    ) {}

    public function execute(UpdateEmployeeInputDto $input): UpdateEmployeeOutputDto
    {
        try {
            $employeeById = $this->getEmployeeById($input);

            $this->assertExistEmployeeByEmail($employeeById, $input);

            $employee = $this->iEmployeeRepository->update(
                new Employee(
                    $input->id,
                    new Name($input->name),
                    new Email($input->email),
                    $employeeById->password(),
                    new Type($input->type),
                    null
                )
            );

            return new UpdateEmployeeOutputDto($employee, $this->notification);

        } catch (\Exception $e) {
            $this->notification->addError([
                'context' => 'update_employee',
                'message' => $e->getMessage(),
            ]);

            return new UpdateEmployeeOutputDto(null, $this->notification);
        }
    }

    private function getEmployeeById(UpdateEmployeeInputDto $input): Employee
    {
        $employee = $this->iEmployeeRepository->getById($input->id);

        if (is_null($employee)) {
            throw new \RuntimeException('Funcionario não encontrado!');
        }

        return $employee;
    }

    private function assertExistEmployeeByEmail(
        Employee $employee,
        UpdateEmployeeInputDto $input
    ): void {
        if ($employee->email()->value() === $input->email) {
            return;
        }

        $existEmployee = $this->iEmployeeRepository->existByEmail(
            new Email($input->email)
        );

        if ($existEmployee) {
            throw new \RuntimeException('Email já cadastrado!');
        }
    }
}
