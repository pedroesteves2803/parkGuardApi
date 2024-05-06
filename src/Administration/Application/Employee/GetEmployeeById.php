<?php

namespace Src\Administration\Application\Employee;

use Src\Administration\Application\Employee\Dtos\GetAllEmployeesOutputDto;
use Src\Administration\Application\Employee\Dtos\GetEmployeeByIdInputDto;
use Src\Administration\Application\Employee\Dtos\GetEmployeeByIdOutputDto;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\Repositories\IEmployeeRepository;
use Src\Shared\Utils\Notification;

final class GetEmployeeById
{
    public function __construct(
        readonly IEmployeeRepository $iEmployeeRepository,
        readonly Notification $notification,
    ) {
    }

    public function execute(GetEmployeeByIdInputDto $input): GetEmployeeByIdOutputDto
    {
        $employee = $this->resolveEmployeeById($input);

        if ($employee instanceof Notification) {
            return new GetEmployeeByIdOutputDto(
                null,
                $this->notification
            );
        }

        return new GetEmployeeByIdOutputDto(
            $employee,
            $this->notification
        );
    }

    private function resolveEmployeeById(GetEmployeeByIdInputDto $input): Employee|Notification
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
}
