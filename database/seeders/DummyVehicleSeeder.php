<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

use App\Models\{
    Vehicle,
    Stock,
    Invoice
};

class DummyVehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'uuid' => Str::uuid()->toString(),
                'name' => 'Toyota Corolla 1.6',
                'vehicle_type' => 'car',
                'type' => 'sedan',
                'price' => 1000000,
                'year' => 2019,
                'color' => 'red',
                'engine' => '1.6',
                'transmission' => 'manual',
                'capacity' => 5,
            ],
            [
                'uuid' => Str::uuid()->toString(),
                'name' => 'Toyota Corolla 1.8',
                'vehicle_type' => 'car',
                'type' => 'sedan',
                'price' => 1200000,
                'year' => 2019,
                'color' => 'red',
                'engine' => '1.8',
                'transmission' => 'manual',
                'capacity' => 5,
            ],
            [
                'uuid' => Str::uuid()->toString(),
                'name' => 'Toyota Avanza 1.3',
                'vehicle_type' => 'car',
                'type' => 'mpv',
                'price' => 800000,
                'year' => 2018,
                'color' => 'red',
                'engine' => '1.3',
                'transmission' => 'manual',
                'capacity' => 7,
            ],
            [
                'uuid' => Str::uuid()->toString(),
                'name' => 'Toyota Avanza 1.5',
                'vehicle_type' => 'car',
                'type' => 'mpv',
                'price' => 900000,
                'year' => 2021,
                'color' => 'red',
                'engine' => '1.5',
                'transmission' => 'manual',
                'capacity' => 7,
            ],
            [
                'uuid' => Str::uuid()->toString(),
                'name' => 'Kawasaki Ninja 250',
                'vehicle_type' => 'motorcycle',
                'price' => 1000000,
                'year' => 2019,
                'color' => 'red',
                'engine' => '250',
                'transmission_type' => 'manual',
                'suspension_type' => 'inverted',
            ],
            [
                'uuid' => Str::uuid()->toString(),
                'name' => 'Kawasaki Ninja 400',
                'vehicle_type' => 'motorcycle',
                'price' => 1200000,
                'year' => 2020,
                'color' => 'green',
                'engine' => '400',
                'transmission_type' => 'manual',
                'suspension_type' => 'inverted',
            ]
        ];

        $vehicles = collect($data);
        // add updated_at and created_at
        $vehicles = $vehicles->map(function ($vehicle) {
            // use ISODate mongodb
            $vehicle['created_at'] = new \MongoDB\BSON\UTCDateTime();
            $vehicle['updated_at'] = new \MongoDB\BSON\UTCDateTime();
            return $vehicle;
        });

        Vehicle::truncate();
        Stock::truncate();

        Vehicle::insert($vehicles->toArray());

        // create stock, init stock
        $vehicles->each(function ($vehicle) {
            $stock = new Stock();
            $stock->uuid = Str::uuid()->toString();
            $stock->vehicle_uuid = $vehicle['uuid'];
            $stock->invoice_uuid = null;
            $stock->stock =  rand(5, 15);
            $stock->type = 'purchase';
            $stock->notes = 'system generated (incoming stock)';
            $stock->save();
        });

        $vehicles->each(function ($vehicle) {
            $qty = rand(-5, -1);
            unset($vehicle['created_at']);
            unset($vehicle['updated_at']);

            $inv = new Invoice();
            $inv->uuid = Str::uuid()->toString();
            $inv->vehicle = $vehicle;
            $inv->qty = abs($qty);
            $inv->type = 'sales';
            $inv->notes = 'system generated (sales)';
            $inv->save();

            $stock = new Stock();
            $stock->uuid = Str::uuid()->toString();
            $stock->vehicle_uuid = $vehicle['uuid'];
            $stock->invoice_uuid = null;
            $stock->stock = $qty;
            $stock->type = 'sales';
            $stock->notes = 'system generated (outgoing stock to somewhere)';
            $stock->save();
        });
    }
}
