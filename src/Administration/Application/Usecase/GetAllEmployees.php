<?php

namespace Src\Administration\Application\Usecase;

use Src\Administration\Application\Dtos\GetAllEmployeesOutputDto;
use Src\Administration\Domain\Repositories\IEmployeeRepository;
use Src\Shared\Utils\Notification;

final readonly class GetAllEmployees
{
    public function __construct(
        public IEmployeeRepository $iEmployeeRepository,
        public Notification        $notification,
    ) {
    }

    public function execute(): GetAllEmployeesOutputDto
    {
        try {
            $employees = $this->iEmployeeRepository->getAll();

            if (is_null($employees)) {
                $this->notification->addError([
                    'context' => 'get_all_employees',
                    'message' => 'Não possui funcionarios!',
                ]);

                return new GetAllEmployeesOutputDto(null, $this->notification);
            }

            return new GetAllEmployeesOutputDto($employees, $this->notification);
        } catch (\Exception $e) {
            $this->notification->addError([
                'context' => 'get_all_employees',
                'message' => $e->getMessage(),
            ]);

            return new GetAllEmployeesOutputDto(null, $this->notification);
        }
    }
}
