<?php

namespace Src\Vehicles\Domain\Entities;

use Src\Shared\Domain\Entities\Entity;
use Src\Shared\Domain\Entities\IAggregator;
use Src\Vehicles\Domain\ValueObjects\Description;
use Src\Vehicles\Domain\ValueObjects\Type;

class Pending extends Entity implements IAggregator
{
    public function __construct(
        readonly ?int $id,
        readonly ?Type $type,
        readonly ?Description $description,
    ) {
    }

    public function id(): int
    {
        return $this->id;
    }

    public function type(): Type
    {
        return $this->type;
    }

    public function description(): Description
    {
        return $this->description;
    }
}
