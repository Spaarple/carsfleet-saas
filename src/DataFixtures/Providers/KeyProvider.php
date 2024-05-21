<?php

namespace App\DataFixtures\Providers;

use App\Enum\StatusKey;

class KeyProvider
{
    /**
     * @return int|string
     */
    public function statusKey(): int|string
    {
        return array_rand(StatusKey::asArrayInverted());
    }
}