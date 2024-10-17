<?php

namespace Src\Administration\Application\Usecase\Employee;

use Src\Administration\Application\Dtos\Employee\DeleteEmployeeByIdInputDto;
use Src\Administration\Application\Dtos\Employee\DeleteEmployeeByIdOutputDto;
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
            $employee = $this->iEmployeeRepository->getById($input->id);

            if (is_null($employee)) {
                $this->notification->addError([
                    'context' => 'delete_employee_by_id',
                    'message' => 'Funcionario não encontrado!',
                ]);

                return new DeleteEmployeeByIdOutputDto(null, $this->notification);
            }

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
}
