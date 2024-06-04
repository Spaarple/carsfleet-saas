<?php

declare(strict_types=1);

namespace App\Enum;

use App\Traits\EnumTrait;

enum GearBox: string
{
    use EnumTrait;

    case MANUAL = 'Manuelle';
    case AUTOMATIC = 'Automatique';
    case SEMI_AUTOMATIC = 'Semi-automatique';
}
