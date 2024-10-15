<?php

namespace Src\Administration\Domain\Entities;

use Src\Administration\Domain\ValueObjects\AdditionalHourPrice;
use Src\Administration\Domain\ValueObjects\Name;
use Src\Administration\Domain\ValueObjects\PricePerHour;
use Src\Shared\Domain\Entities\Entity;
use Src\Shared\Domain\Entities\IAggregateRoot;

class Parking extends Entity implements IAggregateRoot
{
    public function __construct(
        readonly private ?int $id,
        readonly private Name $name,
        readonly private string $responsibleIdentification,
        readonly private Name $responsibleName,
        readonly private PricePerHour $pricePerHour,
        readonly private AdditionalHourPrice $additionalHourPrice
    ) {
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function responsibleIdentification(): string
    {
        return $this->responsibleIdentification;
    }

    public function responsibleName(): Name
    {
        return $this->responsibleName;
    }

    public function pricePerHour(): PricePerHour
    {
        return $this->pricePerHour;
    }

    public function additionalHourPrice(): AdditionalHourPrice
    {
        return $this->additionalHourPrice;
    }
}
