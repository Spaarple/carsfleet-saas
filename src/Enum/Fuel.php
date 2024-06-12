<?php

declare(strict_types=1);

namespace App\Enum;

use App\Traits\EnumTrait;

enum Fuel: string
{
    use EnumTrait;

    case E10 = 'SP95-E10';
    case E5_SP95 = 'SP95';
    case E5_SP98 = 'SP98';
    case B7_GAZOLE = 'Gazole';
    case LPG = 'GPL-c';
    case ELECTRIC = 'Electrique';
    case HYBRID = 'Hybride';
    case HYDROGEN = 'Hydrogène';
    case ETHANOL = 'E85';
}
