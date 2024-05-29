<?php

namespace Src\Administration\Application\Employee;

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
        try {
            $employee = $this->getEmployeeById($input);

            return new GetEmployeeByIdOutputDto($employee, $this->notification);
        } catch (\Exception $e) {
            $this->notification->addError([
                'context' => 'get_employee_by_id',
                'message' => $e->getMessage(),
            ]);

            return new GetEmployeeByIdOutputDto(null, $this->notification);
        }
    }

    private function getEmployeeById(GetEmployeeByIdInputDto $input): Employee
    {
        $employee = $this->iEmployeeRepository->getById($input->id);

        if (is_null($employee)) {
            throw new \Exception('Funcionario não encontrado!');
        }

        return $employee;
    }
}
