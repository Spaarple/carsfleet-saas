<?php

declare(strict_types=1);

namespace App\Enum;

use App\Traits\EnumTrait;

enum Calendar: string
{
    use EnumTrait;

    case DAYS = 'days';
    case MONTHS = 'months';
}
