<?php

namespace App\Entity;

use App\Entity\User\UserEmployed;
use App\Repository\BorrowRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BorrowRepository::class)]
class Borrow
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $startDate = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $endDate = null;

    #[ORM\ManyToOne(inversedBy: 'borrows')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Car $car = null;

    #[ORM\ManyToMany(targetEntity: UserEmployed::class, inversedBy: 'borrows')]
    private Collection $userEmployed;

    #[ORM\ManyToOne(inversedBy: 'borrow')]
    #[ORM\JoinColumn(nullable: false)]
    private ?BorrowMeet $borrowMeet = null;

    #[ORM\ManyToOne(inversedBy: 'driverBorrows')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserEmployed $driver = null;

    public function __construct()
    {
        $this->userEmployed = new ArrayCollection();
    }

    /**
     * @return Uuid|null
     */
    public function getId(): ?Uuid
    {
        return $this->id;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getStartDate(): ?DateTimeImmutable
    {
        return $this->startDate;
    }

    /**
     * @param DateTimeImmutable $startDate
     * @return $this
     */
    public function setStartDate(DateTimeImmutable $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getEndDate(): ?DateTimeImmutable
    {
        return $this->endDate;
    }

    /**
     * @param DateTimeImmutable $endDate
     * @return $this
     */
    public function setEndDate(DateTimeImmutable $endDate): static
    {
        $this->endDate = $endDate;

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
     * @return Collection<int, UserEmployed>
     */
    public function getUserEmployed(): Collection
    {
        return $this->userEmployed;
    }

    /**
     * @param UserEmployed $userEmployed
     * @return $this
     */
    public function addUserEmployed(UserEmployed $userEmployed): static
    {
        if (!$this->userEmployed->contains($userEmployed)) {
            $this->userEmployed->add($userEmployed);
        }

        return $this;
    }

    /**
     * @param UserEmployed $userEmployed
     * @return $this
     */
    public function removeUserEmployed(UserEmployed $userEmployed): static
    {
        $this->userEmployed->removeElement($userEmployed);

        return $this;
    }

    /**
     * @return BorrowMeet|null
     */
    public function getBorrowMeet(): ?BorrowMeet
    {
        return $this->borrowMeet;
    }

    /**
     * @param BorrowMeet|null $borrowMeet
     * @return $this
     */
    public function setBorrowMeet(?BorrowMeet $borrowMeet): static
    {
        $this->borrowMeet = $borrowMeet;

        return $this;
    }

    /**
     * @return UserEmployed|null
     */
    public function getDriver(): ?UserEmployed
    {
        return $this->driver;
    }

    /**
     * @param UserEmployed|null $driver
     * @return $this
     */
    public function setDriver(?UserEmployed $driver): static
    {
        $this->driver = $driver;

        return $this;
    }
}
