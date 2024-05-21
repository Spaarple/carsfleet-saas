<?php

declare(strict_types=1);

namespace App\Enum;

use App\Traits\EnumTrait;

enum Service: string
{
    use EnumTrait;

    case TEACHER = 'Enseignant';
    case MANAGEMENT = 'Management';
    case ADMINISTRATION = 'Administration';
    case MARKETING = 'Commercial';
    case ENGINEER = 'Technicien';
}
