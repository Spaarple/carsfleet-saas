<?php

namespace App\DataFixtures\Providers;

use App\Enum\StatusCars;
use Random\RandomException;

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
        $brands = array_keys($this->randomBrand());
        return $brands[array_rand($brands)];
    }

    /**
     * @param string $brand
     * @return string
     */
    public function model(string $brand): string
    {
        $brandModelPairs = $this->randomBrand();
        return $brandModelPairs[$brand];
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
            'Renault' => 'Clio 2',
            'Peugeot' => '205',
            'Citroën' => 'DS3',
            'Mercedes' => 'Classe A',
            'Audi' => 'A1',
            'Volkwagen' => 'Golf 7',
            'BMW' => 'Série 1',
            'Fiat' => '500',
            'Ford' => 'Fiesta',
            'Toyota' => 'Yaris',
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

    /**
     * @return string
     * @throws RandomException
     */
    public function registrationNumber(): string
    {
        return sprintf('%s-%s-%s',
            $this->randomLetters(),
            $this->randomNumbers(),
            $this->randomLetters()
        );
    }

    /**
     * @return string
     */
    private function randomLetters(): string
    {
        $letters = range('A', 'Z');

        return $letters[array_rand($letters)] . $letters[array_rand($letters)];
    }

    /**
     * @return string
     * @throws RandomException
     */
    private function randomNumbers(): string
    {
        return str_pad(random_int(0, 999), 3, '0', STR_PAD_LEFT);
    }
}