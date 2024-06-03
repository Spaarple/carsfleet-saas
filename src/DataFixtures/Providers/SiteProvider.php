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
    public function region(): string
    {
        $region = array_keys($this->randomRegionAndName());
        return $region[array_rand($region)];
    }

    /**
     * @param string $region
     * @return string
     */
    public function nameSite(string $region): string
    {
        $regionPairNameSite = $this->randomRegionAndName();
        return $regionPairNameSite[$region];
    }

    /**
     * @return string[]
     */
    private function randomRegionAndName(): array
    {
        return [
            'Region Pays de la Loire ' => 'Site de Nantes',
            'Region Bretagne' => 'Site de Rennes',
            'Region Hauts de France' => 'Site de Lille',
            'Region Grand Est' => 'Site de Strasbourg',
            'Region Occitanie' => 'Site de Toulouse',
            'Region Ile de France' => 'Site de Paris',
        ];
    }
}