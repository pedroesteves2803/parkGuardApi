<?php

use Src\Administration\Domain\ValueObjects\ExpirationTime;

test('validates instance expiration time', function () {
    $expirationTime = new ExpirationTime(now());
    expect($expirationTime)->toBeInstanceOf(ExpirationTime::class);
});

it('validates a valid expiration time', function () {
    $currentTime = now();
    $expirationTime = new ExpirationTime($currentTime);
    expect($expirationTime->value())->toBe($currentTime);
});
