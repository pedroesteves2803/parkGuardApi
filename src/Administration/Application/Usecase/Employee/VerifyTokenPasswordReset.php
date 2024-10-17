<?php

namespace Src\Administration\Application\Usecase\Employee;

use Src\Administration\Application\Dtos\Employee\VerifyTokenPasswordResetInputDto;
use Src\Administration\Application\Dtos\Employee\VerifyTokenPasswordResetOutputDto;
use Src\Administration\Domain\Entities\PasswordResetToken;
use Src\Administration\Domain\Repositories\IPasswordResetRepository;
use Src\Administration\Domain\ValueObjects\Token;
use Src\Shared\Utils\Notification;

final readonly class VerifyTokenPasswordReset
{
    public function __construct(
        public IPasswordResetRepository $iPasswordResetRepository,
        public Notification             $notification,
    ) {}

    public function execute(VerifyTokenPasswordResetInputDto $input): VerifyTokenPasswordResetOutputDto
    {
        try {
            $passwordResetToken = $this->getPasswordResetTokenByToken($input);

            if($passwordResetToken === null){
                return new VerifyTokenPasswordResetOutputDto(null, $this->notification);
            }

            return new VerifyTokenPasswordResetOutputDto($passwordResetToken, $this->notification);
        } catch (\Exception $e) {
            $this->notification->addError([
                'context' => 'verify_token_password_reset_employee',
                'message' => $e->getMessage(),
            ]);

            return new VerifyTokenPasswordResetOutputDto(null, $this->notification);
        }
    }

    private function getPasswordResetTokenByToken(VerifyTokenPasswordResetInputDto $input): ?PasswordResetToken
    {
        $passwordResetToken = $this->iPasswordResetRepository->getByToken(new Token($input->token));

        if (is_null($passwordResetToken)) {
            $this->notification->addError([
                'context' => 'verify_token_password_reset_employee',
                'message' => 'Token não existe!',
            ]);

            return null;
        }

        if ($passwordResetToken->isExpired()) {

            $this->notification->addError([
                'context' => 'verify_token_password_reset_employee',
                'message' => 'Token expirado!',
            ]);

            return null;
        }

        return $passwordResetToken;
    }
}
