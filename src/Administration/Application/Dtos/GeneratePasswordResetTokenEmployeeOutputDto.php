<?php

namespace Src\Administration\Application\Dtos;

use Src\Administration\Domain\Entities\PasswordResetToken;
use Src\Shared\Utils\Notification;

final readonly class GeneratePasswordResetTokenEmployeeOutputDto
{
    public function __construct(
        public ?PasswordResetToken $passwordResetToken,
        public Notification        $notification
    ) {
    }
}
