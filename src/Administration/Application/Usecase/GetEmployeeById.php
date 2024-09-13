<?php

namespace Src\Administration\Application\Usecase;

use Src\Administration\Application\Dtos\GetEmployeeByIdInputDto;
use Src\Administration\Application\Dtos\GetEmployeeByIdOutputDto;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\Repositories\IEmployeeRepository;
use Src\Shared\Utils\Notification;

final readonly class GetEmployeeById
{
    public function __construct(
        public IEmployeeRepository $iEmployeeRepository,
        public Notification        $notification,
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
            throw new \RuntimeException('Funcionario não encontrado!');
        }

        return $employee;
    }
}
