<?php

declare(strict_types=1);

namespace App\Enum;

use App\Traits\EnumTrait;

enum StatusCars: string
{
    use EnumTrait;

    case AVAILABLE = 'Disponible';
    case REPAIR = 'En réparation';
    case DELETED = 'Supprimée';
}
