<?php
declare(strict_types=1);

namespace App\Libs\Services;

use App\Libs\Services\Contract\VehicleContract;

class Vehicle implements VehicleContract
{
    public string $engine = 'gasoline';
    public int $year = 0;
    public string $color = 'white';
    public int $price = 0;
}
