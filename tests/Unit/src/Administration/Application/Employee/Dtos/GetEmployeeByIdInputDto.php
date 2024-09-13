<?php

use Src\Administration\Application\Dtos\GetEmployeeByIdInputDto;

it('can create an instance of GetEmployeeByIdInputDto with a valid ID', function () {
    $id = '1';

    $inputDto = new GetEmployeeByIdInputDto($id);

    expect($inputDto)->toBeInstanceOf(GetEmployeeByIdInputDto::class);
    expect($inputDto->id)->toBe($id);
});
