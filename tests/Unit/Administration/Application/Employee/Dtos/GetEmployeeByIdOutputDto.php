<?php

use Src\Administration\Application\Employee\Dtos\GetEmployeeByIdOutputDto;
use Src\Administration\Domain\Entities\Employee;
use Src\Shared\Domain\ValueObjects\Email;
use Src\Shared\Domain\ValueObjects\Name;
use Src\Shared\Domain\ValueObjects\Password;
use Src\Shared\Domain\ValueObjects\Type;
use Src\Shared\Utils\Notification;

it('can create an instance of GetEmployeeByIdOutputDto with a valid employee', function () {
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

    $outputDto = new GetEmployeeByIdOutputDto($employee, $notification);

    expect($outputDto)->toBeInstanceOf(GetEmployeeByIdOutputDto::class);
    expect($outputDto->employee)->toBe($employee);
    expect($outputDto->notification)->toBe($notification);
    expect($outputDto->notification->getErrors())->toBe([]);
});

it('can create an instance of GetEmployeeByIdOutputDto with null employee and error notifications', function () {
    $notification = new Notification();

    $notification->addError([
        'context' => 'test_error',
        'message' => 'test',
    ]);

    $outputDto = new GetEmployeeByIdOutputDto(null, $notification);

    expect($outputDto)->toBeInstanceOf(GetEmployeeByIdOutputDto::class);
    expect($outputDto->employee)->toBeNull();
    expect($outputDto->notification->getErrors())->toBe([
        [
            'context' => 'test_error',
            'message' => 'test',
        ],
    ]);
});
