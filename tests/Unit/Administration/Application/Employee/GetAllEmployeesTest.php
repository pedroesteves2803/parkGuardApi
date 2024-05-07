<?php

use Illuminate\Support\Collection;
use Src\Administration\Application\Employee\DeleteEmployeeById;
use Src\Administration\Application\Employee\Dtos\DeleteEmployeeByIdInputDto;
use Src\Administration\Application\Employee\Dtos\DeleteEmployeeByIdOutputDto;
use Src\Administration\Application\Employee\Dtos\GetAllEmployeesOutputDto;
use Src\Administration\Application\Employee\GetAllEmployees;
use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\Repositories\IEmployeeRepository;
use Src\Shared\Domain\ValueObjects\Email;
use Src\Shared\Domain\ValueObjects\Name;
use Src\Shared\Domain\ValueObjects\Password;
use Src\Shared\Domain\ValueObjects\Type;
use Src\Shared\Utils\Notification;

beforeEach(function () {
    $this->repositoryMock = mock(IEmployeeRepository::class);
});

it('can retrieve all employees from the repository', function () {
    $notification = new Notification();

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

    $this->repositoryMock->shouldReceive('getAll')->once()->andReturn($employees);

    $getAllEmployees = new GetAllEmployees($this->repositoryMock, $notification);

    $outputDto = $getAllEmployees->execute();

    expect($outputDto)->toBeInstanceOf(GetAllEmployeesOutputDto::class);
    expect($outputDto->employees)->toBe($employees);
    $this->assertCount($employees->count(), $outputDto->employees);
    expect($outputDto->notification->getErrors())->toBeEmpty();
});

it('returns error notification when there are no employees in the repository', function () {
    $notification = new Notification();

    $this->repositoryMock->shouldReceive('getAll')->once()->andReturnNull();

    $getAllEmployees = new GetAllEmployees($this->repositoryMock, $notification);

    $outputDto = $getAllEmployees->execute();

    expect($outputDto)->toBeInstanceOf(GetAllEmployeesOutputDto::class);
    expect($outputDto->employees)->toBeNull();
    expect($outputDto->notification->getErrors())->toBe([
        [
            'context' => 'employees_not_found',
            'message' => 'Não possui funcionarios!',
        ],
    ]);
});
