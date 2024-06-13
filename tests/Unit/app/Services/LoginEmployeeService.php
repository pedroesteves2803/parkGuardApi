<?php

use App\Services\LoginEmployeeService;
use Illuminate\Support\Facades\Auth;
use Src\Administration\Domain\ValueObjects\Email;
use Src\Administration\Domain\ValueObjects\Password;
use Src\Administration\Domain\Entities\Employee;

it('login an employee successfully', function () {
    $email = new Email('john.doe@example.com');
    $password = new Password('P@ssword123');

    $mockUser = mock(Employee::class);
    $mockUser->shouldReceive('id')->andReturn(1);
    $mockUser->shouldReceive('name')->andReturn('John Doe');
    $mockUser->shouldReceive('email')->andReturn('john.doe@example.com');
    $mockUser->shouldReceive('password')->andReturn('hashed_password');
    $mockUser->shouldReceive('type')->andReturn(1);
    $mockUser->shouldReceive('token')->andReturn('teste');

    Auth::shouldReceive('user')->once()->andReturn($mockUser);

    $service = new LoginEmployeeService();
    $employee = $service->login($email, $password);

    expect($employee)->toBeInstanceOf(Employee::class);
    expect($employee->id())->toBe(1);
    expect($employee->name()->value())->toBe('John Doe');
    expect($employee->email()->value())->toBe('john.doe@example.com');
    expect($employee->password()->value())->toBe('hashed_password');
    expect($employee->type()->value())->toBe('employee');
    expect($employee->token())->toBe('teste');

});
