<?php

namespace Src\Administration\Application\Employee;

use Src\Administration\Application\Employee\Dtos\CreateEmployeeInputDto;
use Src\Administration\Application\Employee\Dtos\CreateEmployeeOutputDto;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\Repositories\IEmployeeRepository;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\Name;
use Src\Administration\Domain\ValueObjects\Password;
use Src\Administration\Domain\ValueObjects\Type;
use Src\Shared\Utils\Notification;

final class CreateEmployee
{
    public function __construct(
        readonly IEmployeeRepository $iEmployeeRepository,
        readonly Notification $notification,
    ) {
    }

    public function execute(CreateEmployeeInputDto $input): CreateEmployeeOutputDto
    {
        try {
            $this->assertExistCategoryByEmail($input->email);

            $employee = $this->iEmployeeRepository->create(
                new Employee(
                    null,
                    new Name($input->name),
                    new Email($input->email),
                    new Password($input->password),
                    new Type($input->type),
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

    private function assertExistCategoryByEmail(string $employeeEmail): void
    {
        $existEmployee = $this->iEmployeeRepository->existByEmail(
            new Email($employeeEmail)
        );

        if ($existEmployee) {
            throw new \Exception('Email já cadastrado!');
        }
    }
}
