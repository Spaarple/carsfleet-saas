<?php

namespace App\DataFixtures\Providers;

use App\Entity\User\UserAdministratorHeadOffice;
use App\Entity\User\UserAdministratorSite;
use App\Entity\User\UserEmployed;
use App\Entity\User\UserSuperAdministrator;
use App\Enum\Role;
use App\Enum\Service;
use Exception;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class UserProvider
{
    /**
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}


    /**
     * @param string $plainPassword
     * @param string $role
     *
     * @return string
     *
     * @throws Exception
     */
    public function hashPassword(string $plainPassword, string $role): string
    {
        $user = match ($role) {
            Role::ROLE_EMPLOYED->name => new UserEmployed(),
            Role::ROLE_ADMINISTRATOR_SITE->name => new UserAdministratorSite(),
            Role::ROLE_ADMINISTRATOR_HEAD_OFFICE->name => new UserAdministratorHeadOffice(),
            Role::ROLE_SUPER_ADMINISTRATOR->name => new UserSuperAdministrator(),
            default => throw new Exception('Role unknown.'),
        };

        return $this->passwordHasher->hashPassword($user, $plainPassword);
    }

    /**
     * @return int|string
     */
    public function service(): int|string
    {
        return array_rand(Service::asArrayInverted());
    }
}