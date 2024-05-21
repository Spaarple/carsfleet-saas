<?php

namespace App\DataFixtures\Providers;

use App\Entity\User\UserAdministrator;
use App\Entity\User\UserEmployed;
use App\Entity\User\UserSuperAdministrator;
use App\Enum\Role;
use App\Enum\Service;
use Exception;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserProvider
{
    /**
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
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
            Role::ROLE_ADMINISTRATOR->name => new UserAdministrator(),
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