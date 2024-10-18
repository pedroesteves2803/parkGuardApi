<?php

use Src\Administration\Domain\Entities\Employee;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\Name;
use Src\Administration\Domain\ValueObjects\Password;
use Src\Administration\Domain\ValueObjects\Type;

test('validates instance employee', function () {
    $employee = createValidEmployee();
    expect($employee)->toBeInstanceOf(Employee::class);
});

it('validates a valid employee', function () {
    $employee = createValidEmployee();

    expect($employee->id())->toBe(1)
        ->and($employee->name()->value())->toBe('Employee 1')
        ->and($employee->email()->value())->toBe('employee@test.com')
        ->and($employee->password()->value())->toBe('Password@123')
        ->and($employee->type()->value())->toBe(1)
        ->and($employee->hasEmail(new Email('employee@test.com')))->toBeTrue();
});

test('test employee object to string conversion', function () {
    $employee = createValidEmployee();
    $expectedString = "Funcionario ID: {$employee->id()}, Nome: {$employee->name()->value()}, Email: {$employee->email()->value()}, Tipo: {$employee->type()->value()}";
    expect((string) $employee)->toBe($expectedString);
});

function createValidEmployee()
{
    return new Employee(
        1,
        new Name('Employee 1'),
        new Email('employee@test.com'),
        new Password('Password@123'),
        new Type(1),
        null
    );
}
