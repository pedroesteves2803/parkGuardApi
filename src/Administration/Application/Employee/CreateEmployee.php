<?php

namespace Src\Administration\Application\Employee;

use Src\Administration\Application\Employee\Dtos\CreateEmployeeInputDto;
use Src\Administration\Application\Employee\Dtos\CreateEmployeeOutputDto;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\Repositories\IEmployeeRepository;
use Src\Shared\Domain\ValueObjects\Email;
use Src\Shared\Domain\ValueObjects\Name;
use Src\Shared\Domain\ValueObjects\Password;
use Src\Shared\Domain\ValueObjects\Type;
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
        $existEmployee = $this->resolveExistCategoryByEmail($input->email);

        if ($existEmployee instanceof Notification) {
            return new CreateEmployeeOutputDto(
                null,
                $this->notification
            );
        }

        $employee = $this->iEmployeeRepository->create(
            new Employee(
                null,
                new Name($input->name),
                new Email($input->email),
                new Password($input->password),
                new Type($input->type),
            )
        );

        return new CreateEmployeeOutputDto(
            $employee,
            $this->notification
        );
    }

    private function resolveExistCategoryByEmail(string $employeeEmail): bool|Notification
    {
        $existEmployee = $this->iEmployeeRepository->existByEmail(
            new Email($employeeEmail)
        );

        if ($existEmployee) {
            return $this->notification->addError([
                'context' => 'employee_email_already_exists',
                'message' => 'Email já cadastrado!',
            ]);
        }

        return $existEmployee;
    }
}
