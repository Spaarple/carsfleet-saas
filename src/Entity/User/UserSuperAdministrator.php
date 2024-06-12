<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Enum\Role;
use App\Repository\User\UserSuperAdministratorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserSuperAdministratorRepository::class)]
class UserSuperAdministrator extends AbstractUser
{
    public function __construct()
    {
        parent::__construct();
        $this->setRoles([Role::ROLE_SUPER_ADMINISTRATOR->name]);
    }
}
