<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Enum\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setName('admin')
            ->setRole(Role::ADMIN)
            ->setEmail('admin@admin.com')
            ->setPassword($this->hasher->hashPassword($admin, '0FC85x2KgLLZ'));
        $manager->persist($admin);
        $manager->flush();
    }
}