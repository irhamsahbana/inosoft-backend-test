<?php
declare(strict_types=1);

namespace App\Libs\Services;

class Car extends Vehicle
{
    public ?int $capacity;
    public ?string $type;
}
