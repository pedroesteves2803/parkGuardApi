<?php

use Src\Administration\Domain\ValueObjects\PricePerHour;

describe('PricePerHourTest', function (){

    test('validates instance price por hour', function () {
        $pricePerHour = new PricePerHour(20.0);
        expect($pricePerHour)->toBeInstanceOf(PricePerHour::class);
    });

    it('validates a valid price por hour', function () {
        $pricePerHour = new PricePerHour(20.0);
        expect($pricePerHour->value())->toBe(20.0);
    });

    test('throws an exception for an empty price por hour', function () {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Preço por hora não pode ser menor que 0.');
        new PricePerHour(0);
    });

});
