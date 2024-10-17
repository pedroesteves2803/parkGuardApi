<?php

namespace Src\Administration\Application\Usecase\Employee;

use Src\Administration\Application\Dtos\Employee\CreateEmployeeInputDto;
use Src\Administration\Application\Dtos\Employee\CreateEmployeeOutputDto;
use Src\Administration\Domain\Factory\EmployeeFactory;
use Src\Administration\Domain\Repositories\IEmployeeRepository;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Shared\Utils\Notification;

final readonly class CreateEmployee
{
    public function __construct(
        private IEmployeeRepository $iEmployeeRepository,
        private Notification        $notification,
        private EmployeeFactory $employeeFactory
    ) {}

    public function execute(CreateEmployeeInputDto $input): CreateEmployeeOutputDto
    {
        try {
            $existEmployee = $this->iEmployeeRepository->existByEmail(
                new Email($input->email)
            );

            if ($existEmployee) {
                $this->notification->addError([
                    'context' => 'create_employee',
                    'message' => 'Email já cadastrado!',
                ]);

                return new CreateEmployeeOutputDto(null, $this->notification);
            }

            $employee = $this->iEmployeeRepository->create(
                $this->employeeFactory->create(
                    null,
                    $input->name,
                    $input->email,
                    $input->password,
                    $input->type,
                    null
                )
            );

            return new CreateEmployeeOutputDto($employee, $this->notification);
        } catch (\Exception $e) {
            $this->notification->addError([
                'context' => 'create_employee',
                'message' => $e->getMessage(),
            ]);

            return new CreateEmployeeOutputDto(null, $this->notification);
        }
    }
}
