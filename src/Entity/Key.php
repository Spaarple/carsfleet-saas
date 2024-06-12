<?php

namespace App\Entity;

use App\Enum\StatusKey;
use App\Repository\KeyRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: KeyRepository::class)]
#[ORM\Table(name: '`key`')]
class Key
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'carKeys')]
    private ?Car $car = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::STRING, length: 255, enumType: StatusKey::class)]
    private ?StatusKey $status = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;


    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->name ?? 'New Key';
    }

    /**
     * @return Uuid|null
     */
    public function getId(): ?Uuid
    {
        return $this->id;
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
     * @return StatusKey|null
     */
    public function getStatus(): ?StatusKey
    {
        return $this->status;
    }

    /**
     * @param StatusKey $status
     * @return $this
     */
    public function setStatus(StatusKey $status): static
    {
        $this->status = $status;

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