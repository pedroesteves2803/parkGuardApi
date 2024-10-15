<?php

use Src\Administration\Domain\ValueObjects\AdditionalHourPrice;
use Src\Administration\Domain\ValueObjects\Email;

describe('AdditionalHourPriceTest', function (){

    test('validates instance additional hour price', function () {
        $additionalHourPrice = new AdditionalHourPrice(20.0);
        expect($additionalHourPrice)->toBeInstanceOf(AdditionalHourPrice::class);
    });

    it('validates a valid additional hour price', function () {
        $additionalHourPrice = new AdditionalHourPrice(20.0);
        expect($additionalHourPrice->value())->toBe(20.0);
    });

    test('throws an exception for an empty additional hour price', function () {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Preço por hora adicional não pode ser menor que 0.');
        new AdditionalHourPrice(0);
    });

});
