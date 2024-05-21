<?php

namespace App\Entity;

use App\Entity\User\UserAdministrator;
use App\Entity\User\UserEmployed;
use App\Repository\SiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SiteRepository::class)]
class Site
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 20,
        minMessage: 'Nom du site trop court {{ limit }}',
        maxMessage: 'Nom du site trop long {{ limit }}',
    )]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $postalCode = null;

    #[ORM\Column(length: 255)]
    private ?string $region = null;

    #[Assert\NotBlank]
    #[Assert\Country]
    #[ORM\Column(length: 255)]
    private ?string $country = null;

    #[ORM\ManyToOne(inversedBy: 'sites')]
    #[ORM\JoinColumn(nullable: false)]
    private ?HeadOffice $headOffice = null;

    #[ORM\OneToMany(mappedBy: 'site', targetEntity: UserEmployed::class, orphanRemoval: true)]
    private Collection $employees;

    #[ORM\OneToMany(mappedBy: 'site', targetEntity: UserAdministrator::class, orphanRemoval: true)]
    private Collection $administrators;

    #[ORM\OneToMany(mappedBy: 'site', targetEntity: Car::class, orphanRemoval: true)]
    private Collection $cars;

    #[ORM\OneToMany(mappedBy: 'site', targetEntity: BorrowMeet::class)]
    private Collection $borrowMeets;

    #[ORM\OneToMany(mappedBy: 'tripDestination', targetEntity: BorrowMeet::class)]
    private Collection $borrowMeetsTripDestination;

    public function __construct()
    {
        $this->employees = new ArrayCollection();
        $this->administrators = new ArrayCollection();
        $this->cars = new ArrayCollection();
        $this->borrowMeets = new ArrayCollection();
        $this->borrowMeetsTripDestination = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getName();
    }

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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return $this
     */
    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    /**
     * @param string $postalCode
     * @return $this
     */
    public function setPostalCode(string $postalCode): static
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRegion(): ?string
    {
        return $this->region;
    }

    /**
     * @param string $region
     * @return $this
     */
    public function setRegion(string $region): static
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return $this
     */
    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return HeadOffice|null
     */
    public function getHeadOffice(): ?HeadOffice
    {
        return $this->headOffice;
    }

    /**
     * @param HeadOffice|null $headOffice
     * @return $this
     */
    public function setHeadOffice(?HeadOffice $headOffice): static
    {
        $this->headOffice = $headOffice;

        return $this;
    }

    /**
     * @return Collection<int, UserEmployed>
     */
    public function getEmployees(): Collection
    {
        return $this->employees;
    }

    /**
     * @param UserEmployed $employee
     * @return $this
     */
    public function addEmployee(UserEmployed $employee): static
    {
        if (!$this->employees->contains($employee)) {
            $this->employees->add($employee);
            $employee->setSite($this);
        }

        return $this;
    }

    /**
     * @param UserEmployed $employee
     * @return $this
     */
    public function removeEmployee(UserEmployed $employee): static
    {
        if ($this->employees->removeElement($employee) && $employee->getSite() === $this) {
            $employee->setSite(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, UserAdministrator>
     */
    public function getAdministrators(): Collection
    {
        return $this->administrators;
    }

    /**
     * @param UserAdministrator $administrator
     * @return $this
     */
    public function addAdministrator(UserAdministrator $administrator): static
    {
        if (!$this->administrators->contains($administrator)) {
            $this->administrators->add($administrator);
            $administrator->setSite($this);
        }

        return $this;
    }

    /**
     * @param UserAdministrator $administrator
     * @return $this
     */
    public function removeAdministrator(UserAdministrator $administrator): static
    {
        if ($this->administrators->removeElement($administrator) && $administrator->getSite() === $this) {
            $administrator->setSite(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, Car>
     */
    public function getCars(): Collection
    {
        return $this->cars;
    }

    /**
     * @param Car $car
     * @return $this
     */
    public function addCar(Car $car): static
    {
        if (!$this->cars->contains($car)) {
            $this->cars->add($car);
            $car->setSite($this);
        }

        return $this;
    }

    /**
     * @param Car $car
     * @return $this
     */
    public function removeCar(Car $car): static
    {
        if ($this->cars->removeElement($car) && $car->getSite() === $this) {
            $car->setSite(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, BorrowMeet>
     */
    public function getBorrowMeets(): Collection
    {
        return $this->borrowMeets;
    }

    /**
     * @param BorrowMeet $borrowMeet
     * @return $this
     */
    public function addBorrowMeet(BorrowMeet $borrowMeet): static
    {
        if (!$this->borrowMeets->contains($borrowMeet)) {
            $this->borrowMeets->add($borrowMeet);
            $borrowMeet->setSite($this);
        }

        return $this;
    }

    /**
     * @param BorrowMeet $borrowMeet
     * @return $this
     */
    public function removeBorrowMeet(BorrowMeet $borrowMeet): static
    {
        if ($this->borrowMeets->removeElement($borrowMeet) && $borrowMeet->getSite() === $this) {
            $borrowMeet->setSite(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, BorrowMeet>
     */
    public function getBorrowMeetsTripDestination(): Collection
    {
        return $this->borrowMeetsTripDestination;
    }

    /**
     * @param BorrowMeet $borrowMeetsTripDestination
     * @return $this
     */
    public function addBorrowMeetsTripDestination(BorrowMeet $borrowMeetsTripDestination): static
    {
        if (!$this->borrowMeetsTripDestination->contains($borrowMeetsTripDestination)) {
            $this->borrowMeetsTripDestination->add($borrowMeetsTripDestination);
            $borrowMeetsTripDestination->setTripDestination($this);
        }

        return $this;
    }

    /**
     * @param BorrowMeet $borrowMeetsTripDestination
     * @return $this
     */
    public function removeBorrowMeetsTripDestination(BorrowMeet $borrowMeetsTripDestination): static
    {
        if ($this->borrowMeetsTripDestination->removeElement($borrowMeetsTripDestination)
            && $borrowMeetsTripDestination->getTripDestination() === $this) {
            $borrowMeetsTripDestination->setTripDestination(null);
        }

        return $this;
    }
}
