<?php

namespace Src\Administration\Application\Employee;

use Src\Administration\Application\Employee\Dtos\CreateEmployeeInputDto;
use Src\Administration\Application\Employee\Dtos\CreateEmployeeOutputDto;
use Src\Administration\Application\Employee\Dtos\GeneratePasswordResetTokenEmployeeInputDto;
use Src\Administration\Application\Employee\Dtos\GeneratePasswordResetTokenEmployeeOutputDto;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\Entities\PasswordResetToken;
use Src\Administration\Domain\Repositories\IEmployeeRepository;
use Src\Administration\Domain\Repositories\IPasswordResetRepository;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\Name;
use Src\Administration\Domain\ValueObjects\Password;
use Src\Administration\Domain\ValueObjects\Type;
use Src\Shared\Utils\Notification;

final class GeneratePasswordResetTokenEmployee
{
    public function __construct(
        readonly IPasswordResetRepository $iPasswordResetRepository,
        readonly Notification $notification,
    ) {
    }

    public function execute(GeneratePasswordResetTokenEmployeeInputDto $input): GeneratePasswordResetTokenEmployeeOutputDto
    {
        try {
            $employee = $this->assertGetEmployeeByEmail($input->email);

            $passwordResetRepository = $this->iPasswordResetRepository->create(
                new PasswordResetToken(
                    new Email($input->email),
                    null,
                    null
                )
            );

            return new GeneratePasswordResetTokenEmployeeOutputDto($passwordResetRepository, $this->notification);
        } catch (\Exception $e) {
            $this->notification->addError([
                'context' => 'generate_token_employee',
                'message' => $e->getMessage(),
            ]);

            return new CreateEmployeeOutputDto(null, $this->notification);
        }
    }

    private function assertGetEmployeeByEmail(string $employeeEmail): void
    {
        $employee = $this->iPasswordResetRepository->getByEmail(
            new Email($employeeEmail)
        );

        if ($employee) {
            throw new \Exception('Funcionario não existe!');
        }
    }
}
