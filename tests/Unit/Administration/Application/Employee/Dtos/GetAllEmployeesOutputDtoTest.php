<?php

use Illuminate\Support\Collection;
use Src\Administration\Application\Employee\Dtos\GetAllEmployeesOutputDto;
use Src\Administration\Domain\Entities\Employee;
use Src\Shared\Domain\ValueObjects\Email;
use Src\Shared\Domain\ValueObjects\Name;
use Src\Shared\Domain\ValueObjects\Password;
use Src\Shared\Domain\ValueObjects\Type;
use Src\Shared\Utils\Notification;

it('can create an instance of GetAllEmployeesOutputDto with employees collection and notification', function () {
    $id = '1';
    $name = 'John Doe';
    $email = 'john@example.com';
    $password = 'Password@123';
    $type = 1;

    $employees = new Collection();

    for ($i = 0; $i < 10; ++$i) {
        $id = $i + 1;
        $name = 'Employee '.($i + 1);
        $email = 'employee'.($i + 1).'@example.com';
        $password = 'Password'.($i + 1).'@123';
        $type = 1;

        $employees->push(
            new Employee(
                $id,
                new Name($name),
                new Email($email),
                new Password($password),
                new Type($type),
            )
        );
    }

    $notification = new Notification();

    $outputDto = new GetAllEmployeesOutputDto($employees, $notification);

    expect($outputDto)->toBeInstanceOf(GetAllEmployeesOutputDto::class);
    expect($outputDto->employees)->toBe($employees);
    $this->assertCount($employees->count(), $outputDto->employees);
    expect($outputDto->notification)->toBe($notification);
});

it('can create an instance of GetAllEmployeesOutputDto with null employee and error notifications', function () {
    $notification = new Notification();

    $notification->addError([
        'context' => 'test_error',
        'message' => 'test',
    ]);

    $outputDto = new GetAllEmployeesOutputDto(null, $notification);

    expect($outputDto)->toBeInstanceOf(GetAllEmployeesOutputDto::class);
    expect($outputDto->employees)->toBeNull();
    expect($outputDto->notification)->toBe($notification);
});
