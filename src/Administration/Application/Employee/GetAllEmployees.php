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
        try {
            $employees = $this->getEmployees();

            return new GetAllEmployeesOutputDto($employees, $this->notification);
        } catch (\Exception $e) {
            $this->notification->addError([
                'context' => 'get_all_employees',
                'message' => $e->getMessage(),
            ]);

            return new GetAllEmployeesOutputDto(null, $this->notification);
        }
    }

    private function getEmployees(): Collection
    {
        $employees = $this->iEmployeeRepository->getAll();

        if (is_null($employees)) {
            throw new \Exception('Não possui funcionarios!');
        }

        return $employees;
    }
}
