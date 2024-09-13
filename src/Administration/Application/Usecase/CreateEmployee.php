<?php

namespace Src\Administration\Application\Usecase;

use Src\Administration\Application\Dtos\CreateEmployeeInputDto;
use Src\Administration\Application\Dtos\CreateEmployeeOutputDto;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\Repositories\IEmployeeRepository;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\Name;
use Src\Administration\Domain\ValueObjects\Password;
use Src\Administration\Domain\ValueObjects\Type;
use Src\Shared\Utils\Notification;

final readonly class CreateEmployee
{
    public function __construct(
        public IEmployeeRepository $iEmployeeRepository,
        public Notification        $notification,
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
}
