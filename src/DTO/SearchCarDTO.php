<?php

declare(strict_types=1);

namespace App\DTO;

use App\Enum\GearBox;
use DateTimeImmutable;

class SearchCarDTO
{
    /**
     * @var DateTimeImmutable|null $from
     */
    public ?DateTimeImmutable $from = null;

    /**
     * @var DateTimeImmutable|null
     */
    public ?DateTimeImmutable $to = null;

    /**
     * @var GearBox|null
     */
    public ?GearBox $gearbox = null;
}