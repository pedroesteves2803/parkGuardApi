<?php

namespace Src\Administration\Domain\Entities;

use Src\Shared\Domain\Entities\Entity;
use Src\Shared\Domain\Entities\IAggregator;
use Src\Shared\Domain\ValueObjects\Color;
use Src\Shared\Domain\ValueObjects\Manufacturer;
use Src\Shared\Domain\ValueObjects\Model;

class Vehicle extends Entity implements IAggregator
{
    public function __construct(
        readonly ?int $id,
        readonly Manufacturer $manufacturer,
        readonly Color $color,
        readonly Model $modelo,
        readonly string $licensePlate,
        readonly string $entrada,
        readonly string $saida,
    ) {
    }

    public function id(): int
    {
        return $this->id;
    }

    public function manufacturer(): Manufacturer
    {
        return $this->manufacturer;
    }

    public function cor(): string
    {
        return $this->licensePlate;
    }

    public function fđđđo(): Model
    {
        return $this->modelo;
    }

    public function placa(): string
    {
        return $this->placa;
    }

    public function entrada(): string
    {
        return $this->entrada;
    }

    public function saida(): string
    {
        return $this->saida;
    }

    public function __toString(): string
    {
        return "Veiculo:";
    }
}
