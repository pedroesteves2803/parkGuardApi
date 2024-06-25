<?php

namespace Src\Administration\Application\Employee;

use Src\Administration\Application\Employee\Dtos\LogoutEmployeeInputDto;
use Src\Administration\Application\Employee\Dtos\LogoutEmployeeOutputDto;
use Src\Administration\Domain\Services\ILoginEmployeeService;
use Src\Shared\Utils\Notification;

final class LogoutEmployee
{
    public function __construct(
        readonly ILoginEmployeeService $iLoginEmployeeService,
        readonly Notification $notification,
    ) {
    }

    public function execute(LogoutEmployeeInputDto $input): LogoutEmployeeOutputDto
    {
        // try {
            $this->iLoginEmployeeService->logout($input->token);

            return new LogoutEmployeeOutputDto(null, $this->notification);
        // } catch (\Exception $e) {
        //     $this->notification->addError([
        //         'context' => 'logout_employee',
        //         'message' => $e->getMessage(),
        //     ]);

        //     return new LogoutEmployeeOutputDto(null, $this->notification);
        // }
    }
}
