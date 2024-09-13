<?php

namespace Src\Administration\Application\Usecase;

use Src\Administration\Application\Dtos\DeleteEmployeeByIdInputDto;
use Src\Administration\Application\Dtos\DeleteEmployeeByIdOutputDto;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\Repositories\IEmployeeRepository;
use Src\Shared\Utils\Notification;

final readonly class DeleteEmployeeById
{
    public function __construct(
        public IEmployeeRepository $iEmployeeRepository,
        public Notification        $notification,
    ) {
    }

    public function execute(DeleteEmployeeByIdInputDto $input): DeleteEmployeeByIdOutputDto
    {
        try {
            $employee = $this->getCategoryById($input->id);

            $this->iEmployeeRepository->delete($employee->id());

            return new DeleteEmployeeByIdOutputDto(null, $this->notification);
        } catch (\Exception $e) {
            $this->notification->addError([
                'context' => 'delete_employee_by_id',
                'message' => $e->getMessage(),
            ]);

            return new DeleteEmployeeByIdOutputDto(null, $this->notification);
        }
    }

    private function getCategoryById(int $id): Employee
    {
        $employee = $this->iEmployeeRepository->getById($id);

        if (is_null($employee)) {
            throw new \RuntimeException('Funcionario não encontrado!');
        }

        return $employee;
    }
}
