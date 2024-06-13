<?php

namespace Src\Administration\Application\Employee;

use Src\Administration\Application\Employee\Dtos\LoginEmployeeInputDto;
use Src\Administration\Application\Employee\Dtos\LoginEmployeeOutputDto;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\Services\ILoginEmployeeService;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\Password;
use Src\Shared\Utils\Notification;

final class LoginEmployee
{
    public function __construct(
        readonly ILoginEmployeeService $iLoginEmployeeService,
        readonly Notification $notification,
    ) {
    }

    public function execute(LoginEmployeeInputDto $input): LoginEmployeeOutputDto
    {
        try {
            $employee = $this->auth($input);

            return new LoginEmployeeOutputDto($employee, $this->notification);
        } catch (\Exception $e) {
            $this->notification->addError([
                'context' => 'login_employee',
                'message' => $e->getMessage(),
            ]);

            return new LoginEmployeeOutputDto(null, $this->notification);
        }
    }

    private function auth($input): Employee
    {
        $email = new Email($input->email);
        $password = new Password($input->password);

        $employee = $this->iLoginEmployeeService->login($email, $password);

        if (is_null($employee)) {
            throw new \Exception('Email ou senha incorretos!');
        }

        return $employee;
    }
}
