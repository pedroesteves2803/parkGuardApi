<?php

use Src\Administration\Domain\Entities\Employee;
use Src\Shared\Domain\ValueObjects\Email;
use Src\Shared\Domain\ValueObjects\Name;
use Src\Shared\Domain\ValueObjects\Password;
use Src\Shared\Domain\ValueObjects\Type;

test('validates instance employee', function () {
    $employee = createValidEmployee();
    expect($employee)->toBeInstanceOf(Employee::class);
});

it('validates a valid employee', function () {
    $employee = createValidEmployee();
    expect($employee->id())->toBe(1);
    expect($employee->name->value())->toBe('Employee 1');
    expect($employee->email->value())->toBe('employee@test.com');
    expect($employee->password->value())->toBe('Password@123');
    expect($employee->type->value())->toBe(1);
});

test('test employee object to string conversion', function () {
    $employee = createValidEmployee();
    $expectedString = "Funcionario ID: $employee->id, Nome: {$employee->name->value()}, Email: {$employee->email->value()}, Tipo: {$employee->type->value()}";
    expect((string) $employee)->toBe($expectedString);
});

function createValidEmployee()
{
    return new Employee(
        1,
        new Name('Employee 1'),
        new Email('employee@test.com'),
        new Password('Password@123'),
        new Type(1)
    );
}
