<?php

namespace Src\Administration\Application\Usecase\Employee;

use Src\Administration\Application\Dtos\Employee\UpdateEmployeeInputDto;
use Src\Administration\Application\Dtos\Employee\UpdateEmployeeOutputDto;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\Factory\EmployeeFactory;
use Src\Administration\Domain\Repositories\IEmployeeRepository;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Shared\Utils\Notification;

final readonly class UpdateEmployee
{
    public function __construct(
        private IEmployeeRepository $employeeRepository,
        private Notification        $notification,
        private EmployeeFactory $employeeFactory
    ) {}

    public function execute(UpdateEmployeeInputDto $input): UpdateEmployeeOutputDto
    {
        try {
            $employee = $this->getEmployeeById($input->id);

            if (is_null($employee)) {
                return new UpdateEmployeeOutputDto(null, $this->notification);
            }

            if (!$employee->hasEmail(new Email($input->email))) {
                $existsEmployeeWithEmail = $this->employeeRepository->existByEmail(new Email($input->email));

                if ($existsEmployeeWithEmail) {
                    $this->notification->addError([
                        'context' => 'update_employee',
                        'message' => 'Email já cadastrado!',
                    ]);

                    return new UpdateEmployeeOutputDto(null, $this->notification);
                }
            }

            $updatedEmployee = $this->employeeRepository->update(
                $this->employeeFactory->create(
                    $input->id,
                    $input->name,
                    $input->email,
                    $employee->password()->Value(),
                    $input->type,
                    $employee->token()
                )
            );

            return new UpdateEmployeeOutputDto($updatedEmployee, $this->notification);

        } catch (\Exception $e) {
            $this->notification->addError([
                'context' => 'update_employee',
                'message' => $e->getMessage(),
            ]);

            return new UpdateEmployeeOutputDto(null, $this->notification);
        }
    }

    private function getEmployeeById(int $id): ?Employee
    {
        $employee = $this->employeeRepository->getById($id);

        if ($employee === null) {
            $this->notification->addError([
                'context' => 'update_employee',
                'message' => 'Funcionário não encontrado.',
            ]);
        }

        return $employee;
    }
}
