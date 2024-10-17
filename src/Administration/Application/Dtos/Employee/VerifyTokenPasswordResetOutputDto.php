<?php

namespace Src\Administration\Application\Dtos\Employee;

use Src\Administration\Domain\Entities\PasswordResetToken;
use Src\Shared\Utils\Notification;

final readonly class VerifyTokenPasswordResetOutputDto
{
    public function __construct(
        public ?PasswordResetToken $passwordResetToken,
        public Notification        $notification
    ) {
    }
}
