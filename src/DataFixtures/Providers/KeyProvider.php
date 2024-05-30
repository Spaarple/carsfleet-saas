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

    /**
     * @return string
     */
    public function name(): string
    {
        $values = [
            'Clé originale du véhicule',
            'Clé en double'
        ];

        return $values[array_rand($values)];
    }
}