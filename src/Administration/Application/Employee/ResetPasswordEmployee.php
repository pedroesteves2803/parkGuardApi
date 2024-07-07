<?php

namespace Src\Administration\Application\Employee;

use Src\Administration\Application\Employee\Dtos\PasswordResetEmployeeInputDto;
use Src\Administration\Application\Employee\Dtos\PasswordResetEmployeeOutputDto;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\Entities\PasswordResetToken;
use Src\Administration\Domain\Repositories\IEmployeeRepository;
use Src\Administration\Domain\Repositories\IPasswordResetRepository;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\Name;
use Src\Administration\Domain\ValueObjects\Password;
use Src\Administration\Domain\ValueObjects\Token;
use Src\Administration\Domain\ValueObjects\Type;
use Src\Shared\Utils\Notification;

final class ResetPasswordEmployee
{
    public function __construct(
        readonly IPasswordResetRepository $iPasswordResetRepository,
        readonly IEmployeeRepository $iEmployeeRepository,
        readonly Notification $notification,
    ) {}

    public function execute(PasswordResetEmployeeInputDto $input): PasswordResetEmployeeOutputDto
    {
        try {
            $passwordResetToken = $this->getPasswordResetTokenByToken($input);

            $getEmployeeByEmail = $this->getEmployeeByEmail($passwordResetToken->email());

            $employee = new Employee(
                $getEmployeeByEmail->id(),
                new Name($getEmployeeByEmail->name()->value()),
                new Email($getEmployeeByEmail->email()->value()),
                new Password($input->password),
                new Type($getEmployeeByEmail->type()->value()),
                null
            );

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

    private function getPasswordResetTokenByToken(PasswordResetEmployeeInputDto $input): PasswordResetToken
    {
        $passwordReset = $this->iPasswordResetRepository->getByToken(new Token($input->token));

        if (is_null($passwordReset)) {
            throw new \Exception('Token não existe!');
        }

        if ($passwordReset->isExpired()) {
            throw new \Exception('Token expirado!');
        }

        return $passwordReset;
    }

    private function getEmployeeByEmail(Email $email): Employee
    {
        $employee = $this->iEmployeeRepository->getByEmail($email);

        if (is_null($employee)) {
            throw new \Exception('Funcionario não encontrado!');
        }

        return $employee;
    }

    private function updatePassword(PasswordResetToken $passwordResetToken, Employee $employee, PasswordResetEmployeeInputDto $input): Employee
    {
        if ($passwordResetToken->token()->value() !== $input->token) {
            throw new \Exception('Token de redefinição de senha não encontrado ou inválido.');
        }

        $employee = $this->iEmployeeRepository->updatePassword($passwordResetToken, $employee, new Token($input->token));

        if (is_null($employee)) {
            throw new \Exception('Token de redefinição de senha não encontrado ou inválido.');
        }

        return $employee;
    }
}
