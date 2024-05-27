<?php

use Src\Administration\Application\Employee\Dtos\UpdateEmployeeInputDto;

it('can update an instance of UpdateEmployeeInputDto with valid data', function () {
    $id = 1;
    $name = 'John Doe';
    $email = 'john@example.com';
    $password = 'Password@123';
    $type = 1;

    $inputDto = new UpdateEmployeeInputDto($id, $name, $email, $password, $type);

    expect($inputDto)->toBeInstanceOf(UpdateEmployeeInputDto::class);
    expect($inputDto->name)->toBe($name);
    expect($inputDto->email)->toBe($email);
    expect($inputDto->password)->toBe($password);
    expect($inputDto->type)->toBe($type);
});
