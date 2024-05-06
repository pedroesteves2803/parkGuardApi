<?php

namespace Src\Administration\Application\Employee;

use Illuminate\Support\Collection;
use Src\Administration\Application\Employee\Dtos\GetAllEmployeesOutputDto;
use Src\Administration\Domain\Repositories\IEmployeeRepository;
use Src\Shared\Utils\Notification;

final class GetAllEmployees
{
    public function __construct(
        readonly IEmployeeRepository $iEmployeeRepository,
        readonly Notification $notification,
    ) {
    }

    public function execute(): GetAllEmployeesOutputDto
    {
        $employees = $this->resolveEmployees();

        if ($employees instanceof Notification) {
            return new GetAllEmployeesOutputDto(
                null,
                $this->notification
            );
        }

        return new GetAllEmployeesOutputDto(
            $employees,
            $this->notification
        );
    }

    private function resolveEmployees(): Collection|Notification
    {
        $employees = $this->iEmployeeRepository->getAll();

        if (is_null($employees)) {
            return $this->notification->addError([
                'context' => 'employees_not_found',
                'message' => 'Não possui funcionarios!',
            ]);
        }

        return $employees;
    }
}
