<?php

namespace App\DataFixtures\Providers;

use App\Enum\Service;

class SiteProvider
{
    /**
     * @return int|string
     */
    public function service(): int|string
    {
        return array_rand(Service::asArrayInverted());
    }

    /**
     * @return string
     */
    public function nameSite(): string
    {
        return $this->randomNames()[(string) array_rand($this->randomNames())];
    }

    /**
     * @return string
     */
    public function region(): string
    {
        return $this->randomRegion()[(string) array_rand($this->randomRegion())];
    }

    /**
     * @return string[]
     */
    private function randomRegion(): array
    {
        return [
            'Region de PDL',
            'Region de IDF',
            'Region de Auvergne',
            'Region de Est',
            'Region de Nord',
            'Region de Sud',
            'Region de Paris',
            'Region de Marseille',
        ];
    }

    /**
     * @return string[]
     */
    private function randomNames(): array
    {
        return [
            'Site de Nantes',
            'Site de Rennes',
            'Site de Bordeaux',
            'Site de Paris',
            'Site de Lille',
            'Site de Lyon',
            'Site de Marseille',
        ];
    }
}