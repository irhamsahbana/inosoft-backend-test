<?php

namespace App\Libs\Repositories\Contracts;


interface VehicleRepositoryContract
{
    public function getVehicleStock(string $uuid);
    public function getVehicleSalesReport(string $uuid);
    public function getVehicleSalesReports();

    public function saleVehicle(string $uuid, int $quantity);
}
