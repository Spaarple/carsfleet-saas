<?php

declare(strict_types=1);

namespace App\Enum;

use App\Traits\EnumTrait;

enum Role: string
{
    use EnumTrait;

    case ROLE_SUPER_ADMINISTRATOR = 'Super administrateur';
    case ROLE_ADMINISTRATOR = 'administrateur';
    case ROLE_EMPLOYED = 'employé';
    case ROLE_USER = 'utilisateur';
}
