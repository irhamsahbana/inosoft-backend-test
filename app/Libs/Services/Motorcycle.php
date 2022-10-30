<?php
declare(strict_types=1);

namespace App\Libs\Services;

class Motorcycle extends Vehicle
{
    public string $suspensionType = 'mono';
    public string $transmisionType = 'manual';
}
