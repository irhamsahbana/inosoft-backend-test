<?php
declare(strict_types=1);

namespace App\Libs\Services;

use App\Libs\Repositories\Contracts\VehicleRepositoryContract;
use App\Libs\Services\Contracts\VehicleServiceContract;

class Vehicle implements VehicleServiceContract
{
    private VehicleRepositoryContract $mainRepo;

    public ?string $engine;
    public ?int $year;
    public ?string $color;
    public ?int $price;

    public function __construct(VehicleRepositoryContract $repo)
    {
        $this->mainRepo = $repo;
    }

    public function getVehicleStock(string $uuid)
    {
        return $this->mainRepo->getVehicleStock($uuid);
    }

    public function getVehicleSalesReport(string $uuid)
    {
        return $this->mainRepo->getVehicleSalesReport($uuid);
    }

    public function getVehicleSalesReports()
    {
        return $this->mainRepo->getVehicleSalesReports();
    }

    public function saleVehicle(string $uuid, int $quantity)
    {
        return $this->mainRepo->saleVehicle($uuid, $quantity);
    }
}
