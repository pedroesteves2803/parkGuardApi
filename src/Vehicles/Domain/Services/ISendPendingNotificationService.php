<?php

namespace Src\Vehicles\Domain\Services;

use Src\Vehicles\Domain\Entities\Vehicle;

interface ISendPendingNotificationService
{
    public function execute(Vehicle $vehicle): void;
}
