<?php

use Src\Administration\Application\Dtos\Employee\UpdateEmployeeInputDto;

it('can update an instance of UpdateEmployeeInputDto with valid data', function () {
    $id = 1;
    $name = 'John Doe';
    $email = 'john@example.com';
    $type = 1;

    $inputDto = new UpdateEmployeeInputDto($id, $name, $email, $type);

    expect($inputDto)->toBeInstanceOf(UpdateEmployeeInputDto::class);
    expect($inputDto->name)->toBe($name);
    expect($inputDto->email)->toBe($email);
    expect($inputDto->type)->toBe($type);
});
