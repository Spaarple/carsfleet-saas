<?php

namespace App\DataFixtures\Providers;

use App\Enum\StatusCars;

class CarProvider
{
    /**
     * @return int|string
     */
    public function status(): int|string
    {
        return array_rand(StatusCars::asArrayInverted());
    }

    /**
     * @return string
     */
    public function brand(): string
    {
        return $this->randomBrand()[(string) array_rand($this->randomBrand())];
    }

    /**
     * @return string
     */
    public function model(): string
    {
        return $this->randomModel()[(string) array_rand($this->randomModel())];
    }

    /**
     * @return string
     */
    public function color(): string
    {
        return $this->randomColor()[(string) array_rand($this->randomColor())];
    }

    /**
     * @return string[]
     */
    private function randomBrand(): array
    {
        return [
            'Renault',
            'Peugeot',
            'Citroën',
            'Volkswagen',
            'Mercedes',
            'BMW',
            'Audi',
        ];
    }

    /**
     * @return string[]
     */
    private function randomModel(): array
    {
        return [
            'Clio',
            'Megane',
            '308',
            '208',
            'C3',
            'Golf',
            'A3',
            'Série 1',
            'Série 3',
            'Classe A',
            'Classe C',
        ];
    }

    /**
     * @return string[]
     */
    private function randomColor(): array
    {
        return [
            '#ffffff',
            '#34ebb7',
            '#4334eb',
            '#852437',
            '#d3f222',
            '#f26e22',
            '#000000',

        ];
    }
}