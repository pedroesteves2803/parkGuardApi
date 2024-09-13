<?php

use Src\Administration\Application\Dtos\PasswordResetEmployeeOutputDto;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\Name;
use Src\Administration\Domain\ValueObjects\Password;
use Src\Administration\Domain\ValueObjects\Type;
use Src\Shared\Utils\Notification;

it('can create an instance of PasswordResetEmployeeOutputDto with a valid employee', function () {
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
        null
    );

    $notification = new Notification();

    $outputDto = new PasswordResetEmployeeOutputDto($employee, $notification);

    expect($outputDto)->toBeInstanceOf(PasswordResetEmployeeOutputDto::class);
    expect($outputDto->employee)->toBe($employee);
    expect($outputDto->notification)->toBe($notification);
    expect($outputDto->notification->getErrors())->toBe([]);
});

it('can create an instance of PasswordResetEmployeeOutputDto with null employee and error notifications', function () {
    $notification = new Notification();

    $notification->addError([
        'context' => 'test_error',
        'message' => 'test',
    ]);

    $outputDto = new PasswordResetEmployeeOutputDto(null, $notification);

    expect($outputDto)->toBeInstanceOf(PasswordResetEmployeeOutputDto::class);
    expect($outputDto->employee)->toBeNull();
    expect($outputDto->notification->getErrors())->toBe([
        [
            'context' => 'test_error',
            'message' => 'test',
        ],
    ]);
});
