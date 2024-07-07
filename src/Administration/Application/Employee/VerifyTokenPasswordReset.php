<?php

namespace Src\Administration\Application\Employee;

use Src\Administration\Application\Employee\Dtos\PasswordResetEmployeeInputDto;
use Src\Administration\Application\Employee\Dtos\PasswordResetEmployeeOutputDto;
use Src\Administration\Application\Employee\Dtos\VerifyTokenPasswordResetDto;
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

final class VerifyTokenPasswordReset
{
    public function __construct(
        readonly IPasswordResetRepository $iPasswordResetRepository,
        readonly Notification $notification,
    ) {}

    public function execute(VerifyTokenPasswordResetDto $input): PasswordResetEmployeeOutputDto
    {
        try {
            $passwordResetToken = $this->getPasswordResetTokenByToken($input);

            return new PasswordResetEmployeeOutputDto($employee, $this->notification);
        } catch (\Exception $e) {
            $this->notification->addError([
                'context' => 'password_reset_employee',
                'message' => $e->getMessage(),
            ]);

            return new PasswordResetEmployeeOutputDto(null, $this->notification);
        }
    }

    private function getPasswordResetTokenByToken(VerifyTokenPasswordResetDto $input): PasswordResetToken
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
}
