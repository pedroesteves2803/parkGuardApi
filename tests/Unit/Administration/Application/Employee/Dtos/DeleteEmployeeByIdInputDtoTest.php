<?php

use Src\Administration\Application\Employee\Dtos\DeleteEmployeeByIdInputDto;

it('can create an instance of DeleteEmployeeByIdInputDto with a valid ID', function () {
    $id = '1';

    $inputDto = new DeleteEmployeeByIdInputDto($id);

    expect($inputDto)->toBeInstanceOf(DeleteEmployeeByIdInputDto::class);
    expect($inputDto->id)->toBe($id);
});
