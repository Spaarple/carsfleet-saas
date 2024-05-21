<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\Accident;
use App\Entity\Borrow;
use App\Entity\Site;
use App\Enum\Role;
use App\Enum\Service;
use App\Repository\User\UserEmployedRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserEmployedRepository::class)]
class UserEmployed extends AbstractUser
{
    #[ORM\ManyToOne(inversedBy: 'employees')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Site $site = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::INTEGER)]
    private int $matricule;

    #[ORM\Column(type: Types::STRING, length: 255, enumType: Service::class)]
    private Service $service;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $drivingLicense = false;

    #[ORM\ManyToMany(targetEntity: Borrow::class, mappedBy: 'userEmployed')]
    private Collection $borrows;

    #[ORM\OneToMany(mappedBy: 'userEmployed', targetEntity: Accident::class)]
    private Collection $accidents;

    #[ORM\OneToMany(mappedBy: 'driver', targetEntity: Borrow::class)]
    private Collection $driverBorrows;

    public function __construct()
    {
        parent::__construct();
        $this->setRoles([Role::ROLE_EMPLOYED->name]);
        $this->borrows = new ArrayCollection();
        $this->accidents = new ArrayCollection();
        $this->driverBorrows = new ArrayCollection();
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
     * @return int|null
     */
    public function getMatricule(): ?int
    {
        return $this->matricule;
    }

    /**
     * @param int $matricule
     * @return $this
     */
    public function setMatricule(int $matricule): static
    {
        $this->matricule = $matricule;

        return $this;
    }

    /**
     * @return Service
     */
    public function getService(): Service
    {
        return $this->service;
    }

    /**
     * @param Service $service
     * @return $this
     */
    public function setService(Service $service): static
    {
        $this->service = $service;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function isDrivingLicense(): ?bool
    {
        return $this->drivingLicense;
    }

    /**
     * @param bool $drivingLicense
     * @return $this
     */
    public function setDrivingLicense(bool $drivingLicense): static
    {
        $this->drivingLicense = $drivingLicense;

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
            $borrow->addUserEmployed($this);
        }

        return $this;
    }

    /**
     * @param Borrow $borrow
     * @return $this
     */
    public function removeBorrow(Borrow $borrow): static
    {
        if ($this->borrows->removeElement($borrow)) {
            $borrow->removeUserEmployed($this);
        }

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
            $accident->setUser($this);
        }

        return $this;
    }

    /**
     * @param Accident $accident
     * @return $this
     */
    public function removeAccident(Accident $accident): static
    {
        if ($this->accidents->removeElement($accident) && $accident->getUser() === $this) {
            $accident->setUser(null);
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getDriverBorrows(): Collection
    {
        return $this->driverBorrows;
    }

    /**
     * @param Borrow $driverBorrow
     * @return $this
     */
    public function addDriverBorrow(Borrow $driverBorrow): static
    {
        if (!$this->driverBorrows->contains($driverBorrow)) {
            $this->driverBorrows->add($driverBorrow);
            $driverBorrow->setDriver($this);
        }

        return $this;
    }

    /**
     * @param Borrow $driverBorrow
     * @return $this
     */
    public function removeDriverBorrow(Borrow $driverBorrow): static
    {
        if ($this->driverBorrows->removeElement($driverBorrow) && $driverBorrow->getDriver() === $this) {
            $driverBorrow->setDriver(null);
        }

        return $this;
    }
}
