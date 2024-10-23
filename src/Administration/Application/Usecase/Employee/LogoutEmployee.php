<?php

namespace Src\Administration\Application\Usecase\Employee;

use Src\Administration\Application\Dtos\Employee\LogoutEmployeeInputDto;
use Src\Administration\Application\Dtos\Employee\LogoutEmployeeOutputDto;
use Src\Administration\Domain\Services\ILoginEmployeeService;
use Src\Shared\Utils\Notification;

final readonly class LogoutEmployee
{
    public function __construct(
        public ILoginEmployeeService $iLoginEmployeeService,
        public Notification          $notification,
    ) {
    }

    public function execute(LogoutEmployeeInputDto $input): LogoutEmployeeOutputDto
    {
        try {
            $this->iLoginEmployeeService->logout($input->token);

            return new LogoutEmployeeOutputDto(null, $this->notification);
        } catch (\Exception $e) {
            $this->notification->addError([
                'context' => 'logout_employee',
                'message' => $e->getMessage(),
            ]);

            return new LogoutEmployeeOutputDto(null, $this->notification);
        }
    }
}