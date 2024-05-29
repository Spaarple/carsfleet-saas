<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\HeadOffice;
use App\Enum\Role;
use App\Repository\User\UserAdministratorHeadOfficeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserAdministratorHeadOfficeRepository::class)]
class UserAdministratorHeadOffice extends AbstractUser
{
    #[ORM\ManyToOne(inversedBy: 'userAdministratorHeadOffices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?HeadOffice $headOffice = null;

    public function __construct()
    {
        parent::__construct();
        $this->setRoles([Role::ROLE_ADMINISTRATOR_HEAD_OFFICE->name]);
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
}
