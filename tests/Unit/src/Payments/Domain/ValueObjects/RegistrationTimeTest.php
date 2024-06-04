<?php

use Src\Payments\Domain\ValueObjects\RegistrationTime;

test('validates instance registration time', function () {
    $registrationTime = new RegistrationTime(now());
    expect($registrationTime)->toBeInstanceOf(RegistrationTime::class);
});

it('validates a valid date time', function () {
    $time = new \DateTime('2024-05-10 08:00:00');
    $registrationTime = new RegistrationTime($time);
    expect($registrationTime->value())->toBe($time);
});
