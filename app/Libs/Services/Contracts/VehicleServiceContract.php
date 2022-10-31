<?php

namespace App\Libs\Services\Contracts;

interface  VehicleServiceContract
{
    public function getVehicleStock(string $uuid);
    public function getVehicleSalesReport(string $uuid);
    public function getVehicleSalesReports();

    public function saleVehicle(string $uuid, int $quantity);
}
