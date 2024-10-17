<?php

namespace Src\Administration\Application\Usecase\Employee;

use Src\Administration\Application\Dtos\Employee\GeneratePasswordResetTokenEmployeeInputDto;
use Src\Administration\Application\Dtos\Employee\GeneratePasswordResetTokenEmployeeOutputDto;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\Entities\PasswordResetToken;
use Src\Administration\Domain\Repositories\IEmployeeRepository;
use Src\Administration\Domain\Repositories\IPasswordResetRepository;
use Src\Administration\Domain\Services\ISendPasswordResetTokenService;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Shared\Utils\Notification;

final readonly class GeneratePasswordResetTokenEmployee
{
    public function __construct(
        private IPasswordResetRepository $passwordResetRepository,
        private IEmployeeRepository $employeeRepository,
        private ISendPasswordResetTokenService $sendPasswordResetTokenService,
        private Notification $notification
    ) {}

    public function execute(GeneratePasswordResetTokenEmployeeInputDto $input): GeneratePasswordResetTokenEmployeeOutputDto
    {
        try {
            $this->removeExistingPasswordResetTokens($input->email);

            $employee = $this->findEmployeeByEmail($input->email);

            if (is_null($employee)) {
                return new GeneratePasswordResetTokenEmployeeOutputDto(null, $this->notification);
            }

            $passwordResetToken = new PasswordResetToken(
                $employee->email(),
                null,
                null
            );

            $this->passwordResetRepository->create($passwordResetToken);

            $this->sendPasswordResetTokenService->execute($passwordResetToken);

            return new GeneratePasswordResetTokenEmployeeOutputDto($passwordResetToken, $this->notification);
        } catch (\Exception $e) {
            $this->notification->addError([
                'context' => 'generate_token_employee',
                'message' => $e->getMessage(),
            ]);

            return new GeneratePasswordResetTokenEmployeeOutputDto(null, $this->notification);
        }
    }

    private function findEmployeeByEmail(string $email): ?Employee
    {
        $employee = $this->employeeRepository->getByEmail(new Email($email));

        if ($employee === null) {
            $this->notification->addError([
                'context' => 'generate_token_employee',
                'message' => 'Funcionário não encontrado.',
            ]);
        }

        return $employee;
    }

    private function removeExistingPasswordResetTokens(string $email): void
    {
        $existingToken = $this->passwordResetRepository->getByEmail(new Email($email));

        if ($existingToken !== null) {
            $this->passwordResetRepository->delete(new Email($email));
        }
    }


}
