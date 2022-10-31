<?php

namespace App\Libs\Repositories\Finder;
// using mongoDB for this project

class VehicleFinder extends AbstractFinder
{
    private int $year = 0;
    private string $color = '';
    private float $price = 0.0;

    private string $vehicleType = '';
    private string $engine = '';

    private ?string $suspensionType;
    private ?string $transmissionType;

    private int $capacity = 0;
    private string $type = '';

    public function __construct()
    {
        $this->query = \App\Models\Vehicle::query();
    }

    public function setYear(int $year) : void
    {
        $this->year = $year;
    }

    public function setColor(string $color) : void
    {
        $this->color = $color;
    }

    public function setPrice(float $price) : void
    {
        $this->price = $price;
    }

    public function setVehicleType(string $vehicleType) : void
    {
        $this->vehicleType = $vehicleType;
    }

    public function setEngine(string $engine) : void
    {
        $this->engine = $engine;
    }

    public function setSuspensionType(string $suspensionType) : void
    {
        $this->suspensionType = $suspensionType;
    }

    public function setTransmissionType(string $transmissionType) : void
    {
        $this->transmissionType = $transmissionType;
    }

    public function setCapacity(int $capacity) : void
    {
        $this->capacity = $capacity;
    }

    public function setType(string $type) : void
    {
        $this->type = $type;
    }

    public function doQuery()
    {
        if (!empty($this->year))
            $this->query->where('year', $this->year);

        if (!empty($this->color))
            $this->query->where('color', $this->color);

        if (!empty($this->price))
            $this->query->where('price', $this->price);

        if (!empty($this->vehicleType))
            $this->query->where('vehicle_type', $this->vehicleType);

        if (!empty($this->engine))
            $this->query->where('engine', $this->engine);

        if (!empty($this->suspensionType))
            $this->query->where('suspension_type', $this->suspensionType);

        if (!empty($this->transmissionType))
            $this->query->where('transmission_type', $this->transmissionType);

        if (!empty($this->capacity))
            $this->query->where('capacity', $this->capacity);
    }
}
