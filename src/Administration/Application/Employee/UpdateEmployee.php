<?php

namespace Src\Administration\Application\Employee;

use Src\Administration\Application\Employee\Dtos\UpdateEmployeeInputDto;
use Src\Administration\Application\Employee\Dtos\UpdateEmployeeOutputDto;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\Repositories\IEmployeeRepository;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\Name;
use Src\Administration\Domain\ValueObjects\Password;
use Src\Administration\Domain\ValueObjects\Type;
use Src\Shared\Utils\Notification;

final class UpdateEmployee
{
    public function __construct(
        readonly IEmployeeRepository $iEmployeeRepository,
        readonly Notification $notification,
    ) {
    }

    public function execute(UpdateEmployeeInputDto $input): UpdateEmployeeOutputDto
    {
        $employeeById = $this->resolveEmployeeById($input);
        if ($employeeById instanceof Notification) {
            return new UpdateEmployeeOutputDto(
                null,
                $this->notification
            );
        }

        $existEmployee = $this->resolveExistEmployeeByEmail($employeeById, $input);

        if ($existEmployee instanceof Notification) {
            return new UpdateEmployeeOutputDto(
                null,
                $this->notification
            );
        }

        $employee = $this->iEmployeeRepository->update(
            new Employee(
                $input->id,
                new Name($input->name),
                new Email($input->email),
                new Password($input->password),
                new Type($input->type),
            )
        );

        return new UpdateEmployeeOutputDto(
            $employee,
            $this->notification
        );
    }

    private function resolveEmployeeById(UpdateEmployeeInputDto $input): Employee|Notification
    {
        $employee = $this->iEmployeeRepository->getById($input->id);

        if (is_null($employee)) {
            return $this->notification->addError([
                'context' => 'employee_not_found',
                'message' => 'Funcionario não encontrado!',
            ]);
        }

        return $employee;
    }

    private function resolveExistEmployeeByEmail(
        Employee $employee,
        UpdateEmployeeInputDto $input
    ): bool|Notification {
        if ($employee->email->value() === $input->email) {
            return false;
        }

        $existEmployee = $this->iEmployeeRepository->existByEmail(
            new Email($input->email)
        );

        if ($existEmployee) {
            return $this->notification->addError([
                'context' => 'employee_email_already_exists',
                'message' => 'Email já cadastrado!',
            ]);
        }

        return $existEmployee;
    }
}
