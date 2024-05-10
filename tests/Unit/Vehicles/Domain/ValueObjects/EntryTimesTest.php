<?php

namespace Tests\Unit\Shared\Domain\ValueObjects;

use DateTime;
use Src\Vehicles\Domain\ValueObjects\EntryTimes;

test('validates instance entry times', function () {
    $time = new DateTime('now');
    $entryTime = new EntryTimes($time);
    expect($entryTime)->toBeInstanceOf(EntryTimes::class);
});

it('valid entry time', function () {
    $entryTime = new DateTime('2024-05-10 08:00:00');
    $entryTime = new EntryTimes($entryTime);
    expect($entryTime)->toBeInstanceOf(EntryTimes::class);
});
