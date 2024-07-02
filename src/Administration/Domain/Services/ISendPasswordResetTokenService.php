<?php

namespace Src\Administration\Domain\Services;

use Src\Administration\Domain\Entities\PasswordResetToken;

interface ISendPasswordResetTokenService
{
    public function execute(PasswordResetToken $passwordResetToken): void;
}
