<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\Site;
use App\Enum\Role;
use App\Repository\User\UserAdministratorSiteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserAdministratorSiteRepository::class)]
class UserAdministratorSite extends AbstractUser
{
    #[ORM\ManyToOne(inversedBy: 'userAdministratorSite')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Site $site = null;

    public function __construct()
    {
        parent::__construct();
        $this->setRoles([Role::ROLE_ADMINISTRATOR_SITE->name]);
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
}
