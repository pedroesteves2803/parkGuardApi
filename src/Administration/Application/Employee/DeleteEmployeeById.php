<?php

namespace Src\Administration\Application\Employee;

use Src\Administration\Application\Employee\Dtos\CreateEmployeeInputDto;
use Src\Administration\Application\Employee\Dtos\CreateEmployeeOutputDto;
use Src\Administration\Application\Employee\Dtos\DeleteEmployeeByIdInputDto;
use Src\Administration\Application\Employee\Dtos\DeleteEmployeeByIdOutputDto;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\Repositories\IEmployeeRepository;
use Src\Shared\Domain\ValueObjects\Email;
use Src\Shared\Domain\ValueObjects\Name;
use Src\Shared\Domain\ValueObjects\Password;
use Src\Shared\Domain\ValueObjects\Type;
use Src\Shared\Utils\Notification;

final class DeleteEmployeeById
{
    public function __construct(
        readonly IEmployeeRepository $iEmployeeRepository,
        readonly Notification $notification,
    ) {
    }

    public function execute(DeleteEmployeeByIdInputDto $input): DeleteEmployeeByIdOutputDto
    {
        $employee = $this->resolveCategoryById($input->id);

        if ($employee instanceof Notification) {
            return new DeleteEmployeeByIdOutputDto(
                null,
                $this->notification
            );
        }

        $this->iEmployeeRepository->delete($employee->id);

        return new DeleteEmployeeByIdOutputDto(
            null,
            $this->notification
        );
    }


    private function resolveCategoryById(int $id): Employee | Notification {
        $employee = $this->iEmployeeRepository->getById($id);

        if(is_null($employee)){
            return $this->notification->addError([
                'context' => 'employee_not_found',
                'message' => 'Funcionario não encontrado!',
            ]);
        }

        return $employee;
    }
}
