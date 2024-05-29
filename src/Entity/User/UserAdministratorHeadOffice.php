<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\HeadOffice;
use App\Enum\Role;
use App\Repository\User\UserAdministratorRepository;
use App\Repository\User\UserSuperAdministratorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserSuperAdministratorHeadOfficeRepository::class)]
class UserSuperAdministratorHeadOffice extends AbstractUser
{
    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'userSuperAdministratorsHeadOffice')]
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
