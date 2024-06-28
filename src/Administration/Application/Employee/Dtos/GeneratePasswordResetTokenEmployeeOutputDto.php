<?php

namespace Src\Administration\Application\Employee\Dtos;

use Src\Administration\Domain\Entities\PasswordResetToken;
use Src\Shared\Utils\Notification;

final class GeneratePasswordResetTokenEmployeeOutputDto
{
    public function __construct(
        readonly ?PasswordResetToken $passwordResetToken,
        readonly Notification $notification
    ) {
    }
}
