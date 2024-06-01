<?php

namespace Tests\Unit\Shared\Domain\ValueObjects;

use Src\Vehicles\Domain\ValueObjects\DepartureTimes;

test('validates instance departure times', function () {
    $time = new \DateTime('now');
    $departureTimes = new DepartureTimes($time);
    expect($departureTimes)->toBeInstanceOf(DepartureTimes::class);
});

it('valid departure time', function () {
    $departureTime = new \DateTime('2024-05-10 08:00:00');
    $departureTimes = new DepartureTimes($departureTime);
    expect($departureTimes->value())->toBe($departureTime);
});
