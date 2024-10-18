<?php

use Src\Administration\Domain\Entities\Parking;
use Src\Administration\Domain\ValueObjects\AdditionalHourPrice;
use Src\Administration\Domain\ValueObjects\Name;
use Src\Administration\Domain\ValueObjects\PricePerHour;

describe("Parking", function() {

    it('validates instance parking', function () {
        $parking = createValidParking();
        expect($parking)->toBeInstanceOf(Parking::class);
    });

    it('validates a valid parking', function () {
        $parking = createValidParking();

        expect($parking->id())->toBe(1)
            ->and($parking->name()->value())->toBe('Parking 24h')
            ->and($parking->responsibleIdentification())->toBe('123456789')
            ->and($parking->responsibleName()->value())->toBe('Test Responsible')
            ->and($parking->pricePerHour()->value())->toBe(20.0)
            ->and($parking->additionalHourPrice()->value())->toBe(10.0)
            ->and($parking->hasIdentification('123456789'))->toBeTrue();
    });

    function createValidParking(): Parking
    {
        $name = 'Parking 24h';
        $responsibleIdentification = '123456789';
        $responsibleName = 'Test Responsible';
        $pricePerHour = 20.0;
        $additionalHourPrice = 10.0;

        return new Parking(
            1,
            new Name($name),
            $responsibleIdentification,
            new Name($responsibleName),
            new PricePerHour($pricePerHour),
            new AdditionalHourPrice($additionalHourPrice),
        );
    }

});
