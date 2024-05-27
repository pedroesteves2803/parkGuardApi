<?php

use Src\Shared\Utils\Notification;

it('can add and retrieve errors', function () {
    $notification = new Notification();

    expect($notification->getErrors())->toBe([]);

    $error1 = ['context' => 'error1', 'message' => 'Error message 1'];
    $error2 = ['context' => 'error2', 'message' => 'Error message 2'];

    $notification->addError($error1);
    $notification->addError($error2);

    expect($notification->getErrors())->toBe([$error1, $error2]);
});

it('can check if there are errors', function () {
    $notification = new Notification();

    expect($notification->hasErrors())->toBeFalse();

    $notification->addError(['context' => 'error', 'message' => 'Error message']);

    expect($notification->hasErrors())->toBeTrue();
});

it('can retrieve error messages by context', function () {
    $notification = new Notification();

    $notification->addError(['context' => 'error1', 'message' => 'Error message 1']);
    $notification->addError(['context' => 'error2', 'message' => 'Error message 2']);
    $notification->addError(['context' => 'error1', 'message' => 'Another error message']);

    expect($notification->messages('error1'))->toBe('error1: Error message 1,error1: Another error message,');
    expect($notification->messages('error2'))->toBe('error2: Error message 2,');
});
