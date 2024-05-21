<?php

namespace App\Entity;

use App\Entity\User\UserEmployed;
use App\Repository\AccidentRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AccidentRepository::class)]
class Accident
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 5, minMessage: 'Description trop courte {{ limit }}')]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'accidents')]
    private ?Car $car = null;

    #[ORM\ManyToOne(inversedBy: 'accidents')]
    private ?UserEmployed $userEmployed = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $date = null;

    /**
     * @return Uuid|null
     */
    public function getId(): ?Uuid
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Car|null
     */
    public function getCar(): ?Car
    {
        return $this->car;
    }

    /**
     * @param Car|null $car
     * @return $this
     */
    public function setCar(?Car $car): static
    {
        $this->car = $car;

        return $this;
    }

    /**
     * @return UserEmployed|null
     */
    public function getUserEmployed(): ?UserEmployed
    {
        return $this->userEmployed;
    }

    /**
     * @param UserEmployed|null $userEmployed
     * @return $this
     */
    public function setUserEmployed(?UserEmployed $userEmployed): static
    {
        $this->userEmployed = $userEmployed;

        return $this;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getDate(): ?DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @param DateTimeImmutable $date
     * @return $this
     */
    public function setDate(DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }
}
