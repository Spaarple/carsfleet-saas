<?php

namespace App\Entity;

use AllowDynamicProperties;
use App\Entity\User\UserAdministratorHeadOffice;
use App\Repository\HeadOfficeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[AllowDynamicProperties] #[ORM\Entity(repositoryClass: HeadOfficeRepository::class)]
class HeadOffice
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $postalCode = null;

    #[Assert\NotBlank]
    #[Assert\Country]
    #[ORM\Column(length: 255)]
    private ?string $country = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $region = null;

    #[ORM\OneToMany(mappedBy: 'headOffice', targetEntity: Site::class, orphanRemoval: true)]
    private Collection $sites;

    #[ORM\OneToMany(mappedBy: 'headOffice', targetEntity: UserAdministratorHeadOffice::class)]
    private Collection $userAdministratorHeadOffices;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    public function __construct()
    {
        $this->sites = new ArrayCollection();
        $this->userAdministratorHeadOffices = new ArrayCollection();
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
     * @return Collection<int, Site>
     */
    public function getSites(): Collection
    {
        return $this->sites;
    }

    /**
     * @param Site $site
     * @return $this
     */
    public function addSite(Site $site): static
    {
        if (!$this->sites->contains($site)) {
            $this->sites->add($site);
            $site->setHeadOffice($this);
        }

        return $this;
    }

    /**
     * @param Site $site
     * @return $this
     */
    public function removeSite(Site $site): static
    {
        if ($this->sites->removeElement($site) && $site->getHeadOffice() === $this) {
            $site->setHeadOffice(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, UserAdministratorHeadOffice>
     */
    public function getUserAdministratorHeadOffices(): Collection
    {
        return $this->userAdministratorHeadOffices;
    }

    /**
     * @param UserAdministratorHeadOffice $userAdministratorHeadOffices
     * @return $this
     */
    public function addUserAdministratorHeadOffice(UserAdministratorHeadOffice $userAdministratorHeadOffices): static
    {
        if (!$this->userAdministratorHeadOffices->contains($userAdministratorHeadOffices)) {
            $this->userAdministratorHeadOffices->add($userAdministratorHeadOffices);
            $userAdministratorHeadOffices->setHeadOffice($this);
        }

        return $this;
    }

    /**
     * @param UserAdministratorHeadOffice $userAdministratorHeadOffices
     * @return $this
     */
    public function removeUserAdministratorHeadOffice(UserAdministratorHeadOffice $userAdministratorHeadOffices): static
    {
        if ($this->userAdministratorHeadOffices->removeElement($userAdministratorHeadOffices) && $userAdministratorHeadOffices->getHeadOffice() === $this) {
            $userAdministratorHeadOffices->setHeadOffice(null);
        }

        return $this;
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
}
