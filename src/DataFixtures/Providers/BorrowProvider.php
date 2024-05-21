<?php

namespace App\DataFixtures\Providers;

use DateTimeImmutable;
use Faker\Factory;
use Faker\Generator;

class BorrowProvider
{

    private Generator $faker;

    public function __construct() {
        $this->faker = Factory::create('fr_FR');
    }

    /**
     * @return DateTimeImmutable
     */
    public function startDate(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromMutable($this->faker->dateTime('now'));
    }

    /**
     * @param DateTimeImmutable $startDate
     * @return DateTimeImmutable
     */
    public function endDate(DateTimeImmutable $startDate): DateTimeImmutable
    {
        $daysToAdd = $this->faker->numberBetween(1, 10);
        return $startDate->modify("+$daysToAdd days");
    }
}