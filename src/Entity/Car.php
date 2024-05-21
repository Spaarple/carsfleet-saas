<?php

namespace App\Entity;

use App\Enum\StatusCars;
use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CarRepository::class)]
class Car
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 17,
        max: 17,
        minMessage: 'Numéro de serie incorrect {{ limit }}',
        maxMessage: 'Numéro de serie incorrect {{ limit }}',
    )]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $serialNumber = null;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 20,
        minMessage: 'Nom de marque trop court {{ limit }}',
        maxMessage: 'Nom de marque trop long {{ limit }}',
    )]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $brand = null;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 20,
        minMessage: 'Nom de modèle trop court {{ limit }}',
        maxMessage: 'Nom de modèle trop long {{ limit }}',
    )]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $model = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $color = null;

    #[Assert\NotBlank]
    #[Assert\Positive]
    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $passengerQuantity = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::STRING, length: 255, enumType: StatusCars::class)]
    private ?StatusCars $status = null;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 7,
        max: 7,
        minMessage: 'Immatriculation incorrect {{ limit }}',
        maxMessage: 'Immatriculation incorrect {{ limit }}',
    )]
    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $registrationNumber = null;

    #[ORM\OneToMany(mappedBy: 'car', targetEntity: Borrow::class)]
    private Collection $borrows;

    #[ORM\ManyToOne(inversedBy: 'cars')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Site $site = null;

    #[ORM\OneToMany(mappedBy: 'car', targetEntity: Accident::class)]
    private Collection $accidents;

    #[Assert\Count(
        min: 1,
        max: 2,
        minMessage: 'Nombre de clé minimum atteint',
        maxMessage: 'Nombre de clé maximum atteint'
    )]
    #[ORM\OneToMany(mappedBy: 'car', targetEntity: Key::class)]
    private Collection $carKeys;

    public function __construct()
    {
        $this->borrows = new ArrayCollection();
        $this->accidents = new ArrayCollection();
        $this->carKeys = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('%s - %s', $this->getModel(), $this->getBrand());
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
    public function getSerialNumber(): ?string
    {
        return $this->serialNumber;
    }

    /**
     * @param string $serialNumber
     * @return $this
     */
    public function setSerialNumber(string $serialNumber): static
    {
        $this->serialNumber = $serialNumber;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBrand(): ?string
    {
        return $this->brand;
    }

    /**
     * @param string $brand
     * @return $this
     */
    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getModel(): ?string
    {
        return $this->model;
    }

    /**
     * @param string $model
     * @return $this
     */
    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * @param string $color
     * @return $this
     */
    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPassengerQuantity(): ?int
    {
        return $this->passengerQuantity;
    }

    /**
     * @param int $passengerQuantity
     * @return $this
     */
    public function setPassengerQuantity(int $passengerQuantity): static
    {
        $this->passengerQuantity = $passengerQuantity;

        return $this;
    }

    /**
     * @return StatusCars|null
     */
    public function getStatus(): ?StatusCars
    {
        return $this->status;
    }

    /**
     * @param StatusCars $status
     * @return $this
     */
    public function setStatus(StatusCars $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRegistrationNumber(): ?string
    {
        return $this->registrationNumber;
    }

    /**
     * @param string $registrationNumber
     * @return $this
     */
    public function setRegistrationNumber(string $registrationNumber): static
    {
        $this->registrationNumber = $registrationNumber;

        return $this;
    }

    /**
     * @return Collection<int, Borrow>
     */
    public function getBorrows(): Collection
    {
        return $this->borrows;
    }

    /**
     * @param Borrow $borrow
     * @return $this
     */
    public function addBorrow(Borrow $borrow): static
    {
        if (!$this->borrows->contains($borrow)) {
            $this->borrows->add($borrow);
            $borrow->setCar($this);
        }

        return $this;
    }

    /**
     * @param Borrow $borrow
     * @return $this
     */
    public function removeBorrow(Borrow $borrow): static
    {
        if ($this->borrows->removeElement($borrow) && $borrow->getCar() === $this) {
            $borrow->setCar(null);
        }

        return $this;
    }

    /**
     * @return Site|null
     */
    public function getSite(): ?Site
    {
        return $this->site;
    }

    /**
     * @param Site|null $site
     * @return $this
     */
    public function setSite(?Site $site): static
    {
        $this->site = $site;

        return $this;
    }

    /**
     * @return Collection<int, Accident>
     */
    public function getAccidents(): Collection
    {
        return $this->accidents;
    }

    /**
     * @param Accident $accident
     * @return $this
     */
    public function addAccident(Accident $accident): static
    {
        if (!$this->accidents->contains($accident)) {
            $this->accidents->add($accident);
            $accident->setCar($this);
        }

        return $this;
    }

    /**
     * @param Accident $accident
     * @return $this
     */
    public function removeAccident(Accident $accident): static
    {
        if ($this->accidents->removeElement($accident) && $accident->getCar() === $this) {
            $accident->setCar(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, Key>
     */
    public function getCarKeys(): Collection
    {
        return $this->carKeys;
    }

    /**
     * @param Key $carKey
     * @return $this
     * @throws Exception
     */
    public function addCarKey(Key $carKey): static
    {
        if (count($this->carKeys) >= 2) {
            throw new Exception('Un véhicule ne peut avoir que 2 clés au maximum');
        }

        if (!$this->carKeys->contains($carKey)) {
            $this->carKeys->add($carKey);
            $carKey->setCar($this);
        }

        return $this;
    }

    /**
     * @param Key $carKey
     * @return $this
     */
    public function removeCarKey(Key $carKey): static
    {
        if ($this->carKeys->removeElement($carKey) && $carKey->getCar() === $this) {
            $carKey->setCar(null);
        }

        return $this;
    }
}
