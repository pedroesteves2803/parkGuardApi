<?php

use Src\Administration\Application\Employee\Dtos\CreateEmployeeInputDto;

it('can create an instance of CreateEmployeeInputDto', function () {
    $id = '1';
    $name = 'John Doe';
    $email = 'john@example.com';
    $password = 'Password@123';
    $type = 1;

    $inputDto = new CreateEmployeeInputDto($id, $name, $email, $password, $type);

    expect($inputDto)->toBeInstanceOf(CreateEmployeeInputDto::class);
    expect($inputDto->id)->toBe($id);
    expect($inputDto->name)->toBe($name);
    expect($inputDto->email)->toBe($email);
    expect($inputDto->password)->toBe($password);
    expect($inputDto->type)->toBe($type);
});
