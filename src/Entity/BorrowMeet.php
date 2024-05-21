<?php

namespace App\Entity;

use App\Repository\BorrowMeetRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BorrowMeetRepository::class)]
class BorrowMeet
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'borrowMeets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Site $site = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $date = null;

    #[ORM\OneToMany(mappedBy: 'borrowMeet', targetEntity: Borrow::class)]
    private Collection $borrow;

    #[ORM\ManyToOne(inversedBy: 'borrowMeetsTripDestination')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Site $tripDestination = null;

    public function __construct()
    {
        $this->borrow = new ArrayCollection();
    }

    /**
     * @return Uuid|null
     */
    public function getId(): ?Uuid
    {
        return $this->id;
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

    /**
     * @return Collection<int, Borrow>
     */
    public function getBorrow(): Collection
    {
        return $this->borrow;
    }

    /**
     * @param Borrow $borrow
     * @return $this
     */
    public function addBorrow(Borrow $borrow): static
    {
        if (!$this->borrow->contains($borrow)) {
            $this->borrow->add($borrow);
            $borrow->setBorrowMeet($this);
        }

        return $this;
    }

    /**
     * @param Borrow $borrow
     * @return $this
     */
    public function removeBorrow(Borrow $borrow): static
    {
        if ($this->borrow->removeElement($borrow) && $borrow->getBorrowMeet() === $this) {
            $borrow->setBorrowMeet(null);
        }

        return $this;
    }

    /**
     * @return Site|null
     */
    public function getTripDestination(): ?Site
    {
        return $this->tripDestination;
    }

    /**
     * @param Site|null $tripDestination
     * @return $this
     */
    public function setTripDestination(?Site $tripDestination): static
    {
        $this->tripDestination = $tripDestination;

        return $this;
    }
}
