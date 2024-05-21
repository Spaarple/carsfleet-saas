<?php

declare(strict_types=1);

namespace App\Enum;

use App\Traits\EnumTrait;

enum StatusKey: string
{
    use EnumTrait;

    case AT_THE_RECEPTION = 'A la réception';
    case AT_THE_BORROWER = 'Auprès de l\'emprunteur';
}
