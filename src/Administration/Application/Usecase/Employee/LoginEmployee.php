<?php

namespace Src\Administration\Application\Usecase\Employee;

use Src\Administration\Application\Dtos\Employee\LoginEmployeeInputDto;
use Src\Administration\Application\Dtos\Employee\LoginEmployeeOutputDto;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\Services\ILoginEmployeeService;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\Password;
use Src\Shared\Utils\Notification;

final readonly class LoginEmployee
{
    public function __construct(
        public ILoginEmployeeService $iLoginEmployeeService,
        public Notification          $notification,
    ) {}

    public function execute(LoginEmployeeInputDto $input): LoginEmployeeOutputDto
    {
        try {
            $employee = $this->auth($input);

            if(is_null($employee)){
                return new LoginEmployeeOutputDto(null, $this->notification);
            }

            return new LoginEmployeeOutputDto($employee, $this->notification);
        } catch (\Exception $e) {
            $this->notification->addError([
                'context' => 'login_employee',
                'message' => $e->getMessage(),
            ]);

            return new LoginEmployeeOutputDto(null, $this->notification);
        }
    }

    private function auth($input): ?Employee
    {
        $email = new Email($input->email);
        $password = new Password($input->password);

        $employee = $this->iLoginEmployeeService->login($email, $password);

        if (is_null($employee)) {
            $this->notification->addError([
                'context' => 'login_employee',
                'message' => 'Email ou senha incorretos!',
            ]);
            return null;
        }

        return $employee;
    }
}
