<?php
declare(strict_types=1);

namespace App\Libs\Repositories;

use App\Libs\Repositories\Contracts\VehicleRepositoryContract;
use App\Models\Invoice;
use App\Models\Stock;
use App\Models\Vehicle;

class VehicleRepository implements VehicleRepositoryContract
{
    private Invoice $invoiceModel;
    private Stock $stockModel;
    private Vehicle $vehicleModel;

    public function __construct()
    {
        $this->invoiceModel = new Invoice();
        $this->stockModel = new Stock();
        $this->vehicleModel = new Vehicle();
    }

    public function getVehicleStock(string $uuid)
    {
        // sum the quantity of all the stocks of the vehicle
        $stock = $this->stockModel->where('vehicle_uuid', $uuid)->sum('stock');
        $vehicle = $this->vehicleModel->where('uuid', $uuid)->first() ?? [];
        if ($vehicle) {
            $vehicle = $vehicle->toArray();
        }

        if (!empty($vehicle)) {
            $vehicle['stock'] = $stock;
        }

        return $vehicle;
    }

    public function getVehicleSalesReport(string $uuid)
    {
        // get the sales report of the vehicle
        $salesReport = $this->invoiceModel
                            ->where('vehicle.uuid', $uuid)
                            ->where('type', 'sales')
                            ->get();

        if ($salesReport) {
            $salesReport = $salesReport->map(function ($item) {
                $item['total'] = $item->qty * $item->vehicle['price'];
                unset($item['_id']);
                return $item;
            })->toArray();
        } else {
            $salesReport = [];
        }

        $vehicle = $this->vehicleModel
                        ->where('uuid', $uuid)
                        ->first();

        if ($vehicle) {
            $vehicle = $vehicle->toArray();
        } else {
            $vehicle = [];
        }

        if (!empty($vehicle)) {
            $v = [];
            $v['uuid'] = $vehicle['uuid'];
            $v['name'] = $vehicle['name'];
            $v['sales_report'] = $salesReport;
            return $v;
        } else {
            return [];
        }
    }

    public function getVehicleSalesReports()
    {

    }

    public function saleVehicle(string $uuid, int $quantity)
    {
        // check if the vehicle is available
        $v = $this->getVehicleStock($uuid);
        if (empty($v)) {
           false;
        }

        if ($v['stock'] < $quantity) {
            return false;
        }


        unset($v['stock']);

        // create the invoice
        $this->invoiceModel->uuid = \Illuminate\Support\Str::uuid()->toString();
        $this->invoiceModel->vehicle = $v;
        $this->invoiceModel->qty = $quantity;
        $this->invoiceModel->type = 'sales';
        $this->invoiceModel->save();

        // update the stock
        $this->stockModel->uuid = \Illuminate\Support\Str::uuid()->toString();
        $this->stockModel->vehicle_uuid = $uuid;
        $this->stockModel->invoice_uuid = $this->invoiceModel->uuid;
        $this->stockModel->stock = -$quantity;
        $this->stockModel->type = 'sales';
        $this->stockModel->notes = 'Sold ' . $quantity . ' units of ' . $v['name'];
        $this->stockModel->save();

        return $this->invoiceModel->toArray();
    }
}
