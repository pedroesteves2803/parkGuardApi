<?php

use Src\Payments\Domain\ValueObjects\DateTime;

test('validates instance date time', function () {
    $dateTime = new DateTime(now());
    expect($dateTime)->toBeInstanceOf(DateTime::class);
});

it('validates a valid date time', function () {
    $time = new \DateTime('2024-05-10 08:00:00');
    $dateTime = new DateTime($time);
    expect($dateTime->value())->toBe($time);
});
