<?php

use Src\Administration\Application\Employee\Dtos\CreateEmployeeOutputDto;
use Src\Administration\Domain\Entities\Employee;
use Src\Shared\Domain\ValueObjects\Email;
use Src\Shared\Domain\ValueObjects\Name;
use Src\Shared\Domain\ValueObjects\Password;
use Src\Shared\Domain\ValueObjects\Type;
use Src\Shared\Utils\Notification;

it('can create an instance of CreateEmployeeOutputDto', function () {
    $id = '1';
    $name = 'John Doe';
    $email = 'john@example.com';
    $password = 'Password@123';
    $type = 1;

    $employee = new Employee(
        $id,
        new Name($name),
        new Email($email),
        new Password($password),
        new Type($type),
    );

    $notification = new Notification();

    $outputDto = new CreateEmployeeOutputDto($employee, $notification);

    expect($outputDto)->toBeInstanceOf(CreateEmployeeOutputDto::class);
    expect($outputDto->employee)->toBe($employee);
    expect($outputDto->notification)->toBe($notification);
});
