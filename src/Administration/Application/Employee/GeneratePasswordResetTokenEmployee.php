<?php

namespace Src\Administration\Application\Employee;

use Src\Administration\Application\Employee\Dtos\GeneratePasswordResetTokenEmployeeInputDto;
use Src\Administration\Application\Employee\Dtos\GeneratePasswordResetTokenEmployeeOutputDto;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\Entities\PasswordResetToken;
use Src\Administration\Domain\Repositories\IEmployeeRepository;
use Src\Administration\Domain\Repositories\IPasswordResetRepository;
use Src\Administration\Domain\Services\ISendPasswordResetTokenService;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Shared\Utils\Notification;

final class GeneratePasswordResetTokenEmployee
{
    public function __construct(
        readonly IPasswordResetRepository $iPasswordResetRepository,
        readonly IEmployeeRepository $iEmployeeRepository,
        readonly ISendPasswordResetTokenService $iSendPasswordResetTokenService,
        readonly Notification $notification,
    ) {
    }

    public function execute(GeneratePasswordResetTokenEmployeeInputDto $input): GeneratePasswordResetTokenEmployeeOutputDto
    {
        try {
            $this->handlePasswordResetForEmail($input->email);
            $employee = $this->assertGetEmployeeByEmail($input->email);

            $passwordResetRepository = $this->iPasswordResetRepository->create(
                new PasswordResetToken(
                    $employee->email(),
                    null,
                    null
                )
            );

            $this->iSendPasswordResetTokenService->execute($passwordResetRepository);

            return new GeneratePasswordResetTokenEmployeeOutputDto($passwordResetRepository, $this->notification);
        } catch (\Exception $e) {
            $this->notification->addError([
                'context' => 'generate_token_employee',
                'message' => $e->getMessage(),
            ]);

            return new GeneratePasswordResetTokenEmployeeOutputDto(null, $this->notification);
        }
    }

    private function assertGetEmployeeByEmail(string $employeeEmail): Employee
    {
        $employee = $this->iEmployeeRepository->getByEmail(
            new Email($employeeEmail)
        );

        if (!$employee) {
            throw new \Exception('Funcionário não existe!');
        }

        return $employee;
    }

    private function handlePasswordResetForEmail(string $employeeEmail): void
    {
        $employee = $this->iPasswordResetRepository->getByEmail(
            new Email($employeeEmail)
        );

        if ($employee) {
            $this->iPasswordResetRepository->delete(
                new Email($employeeEmail)
            );
        }
    }
}
