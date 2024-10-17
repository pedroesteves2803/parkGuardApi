<?php

namespace Src\Administration\Application\Usecase\Employee;

use Src\Administration\Application\Dtos\Employee\PasswordResetEmployeeInputDto;
use Src\Administration\Application\Dtos\Employee\PasswordResetEmployeeOutputDto;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\Entities\PasswordResetToken;
use Src\Administration\Domain\Repositories\IEmployeeRepository;
use Src\Administration\Domain\Repositories\IPasswordResetRepository;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\Token;
use Src\Shared\Utils\Notification;

final readonly class ResetPasswordEmployee
{
    public function __construct(
        private IPasswordResetRepository $iPasswordResetRepository,
        private IEmployeeRepository      $iEmployeeRepository,
        private Notification             $notification,
    ) {}

    public function execute(PasswordResetEmployeeInputDto $input): PasswordResetEmployeeOutputDto
    {
        try {
            $passwordResetToken = $this->getPasswordResetTokenByToken($input);

            if(is_null($passwordResetToken) && $this->notification->hasErrors()){
                return new PasswordResetEmployeeOutputDto(null, $this->notification);
            }

            $employee = $this->getEmployeeByEmail($passwordResetToken->email());

            if(is_null($employee) && $this->notification->hasErrors()){
                return new PasswordResetEmployeeOutputDto(null, $this->notification);
            }

            $employee = $this->updatePassword($passwordResetToken, $employee, $input);

            return new PasswordResetEmployeeOutputDto($employee, $this->notification);
        } catch (\Exception $e) {
            $this->notification->addError([
                'context' => 'password_reset_employee',
                'message' => $e->getMessage(),
            ]);

            return new PasswordResetEmployeeOutputDto(null, $this->notification);
        }
    }

    private function getPasswordResetTokenByToken(PasswordResetEmployeeInputDto $input): ?PasswordResetToken
    {
        $passwordReset = $this->iPasswordResetRepository->getByToken(new Token($input->token));

        if (is_null($passwordReset)) {
            $this->notification->addError([
                'context' => 'password_reset_employee',
                'message' => 'Token não existe!',
            ]);

            return null;
        }

        if ($passwordReset->isExpired()) {
            $this->notification->addError([
                'context' => 'password_reset_employee',
                'message' => 'Token expirado!',
            ]);

            return null;
        }

        return $passwordReset;
    }

    private function getEmployeeByEmail(Email $email): ?Employee
    {
        $employee = $this->iEmployeeRepository->getByEmail($email);

        if (is_null($employee)) {
            $this->notification->addError([
                'context' => 'password_reset_employee',
                'message' => 'Funcionario não encontrado!',
            ]);

            return null;
        }

        return $employee;
    }

    private function updatePassword(PasswordResetToken $passwordResetToken, Employee $employee, PasswordResetEmployeeInputDto $input): ?Employee
    {
        if ($passwordResetToken->token()->value() !== $input->token) {

            $this->notification->addError([
                'context' => 'password_reset_employee',
                'message' => 'Token de redefinição de senha não encontrado ou inválido.',
            ]);

            return null;
        }

        $employee = $this->iEmployeeRepository->updatePassword($passwordResetToken, $employee, new Token($input->token));

        if (is_null($employee)) {

            $this->notification->addError([
                'context' => 'password_reset_employee',
                'message' => 'Token de redefinição de senha não encontrado ou inválido.',
            ]);

            return null;
        }

        return $employee;
    }
}
