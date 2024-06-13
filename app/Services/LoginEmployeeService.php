<?php

namespace App\Services;

use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Src\Administration\Domain\Services\ILoginEmployeeService;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\Password;

class LoginEmployeeService implements ILoginEmployeeService
{

    public function login(Email $email, Password $password): ?string
    {
        $credentials = ['email' => $email->value(), 'password' => $password->value()];

        if ($token = JWTAuth::attempt($credentials)) {
            return $token;
        }

        return null;
    }

    public function logout(string $token): void
    {
        JWTAuth::invalidate($token);
    }
}
