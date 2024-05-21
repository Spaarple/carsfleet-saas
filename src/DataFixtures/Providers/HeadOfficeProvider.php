<?php

namespace App\DataFixtures\Providers;

class HeadOfficeProvider
{
    /**
     * @return string
     */
    public function nameHeadOffice(): string
    {
        return $this->randomNames()[(string) array_rand($this->randomNames())];
    }

    /**
     * @return string[]
     */
    private function randomNames(): array
    {
        return [
            'Siège Social Paris',
            'Siège Social Bruxelles',
            'Siège Social New York',
            'Siège Social Madrid',
            'Siège Social Londre',
            'Siège Social Topkyo',
            'Siège Social Lisbonne',
            'Siège Social Athènes',
        ];
    }
}