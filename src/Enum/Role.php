<?php

declare(strict_types=1);

namespace App\Enum;

use App\Traits\EnumTrait;

enum Role: string
{
    use EnumTrait;

    case ROLE_SUPER_ADMINISTRATOR = 'Administrateur Général';
    case ROLE_ADMINISTRATOR_HEAD_OFFICE = 'Responsable de l\'entreprise';
    case ROLE_ADMINISTRATOR_SITE = 'administrateur du site';
    case ROLE_EMPLOYED = 'employé';
    case ROLE_USER = 'utilisateur';
}
