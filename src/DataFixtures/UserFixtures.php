<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        for ($nbUsers = 1; $nbUsers <= 5; $nbUsers++) {
            $user = new User();

            $user->setUsername(sprintf('user+%d', $nbUsers));
            $user->setEmail(sprintf('user+%d@gmail.com', $nbUsers));
            $user->setPassword($this->encoder->hashPassword($user, 'password'));
            $user->setRole('ROLE_ADMIN');
            $manager->persist($user);

            $this->addReference('user_'. $nbUsers, $user);
        }

        for ($nbUsers = 6; $nbUsers <= 10; $nbUsers++) {
            $user = new User();

            $user->setUsername(sprintf('user+%d', $nbUsers));
            $user->setEmail(sprintf('user+%d@gmail.com', $nbUsers));
            $user->setPassword($this->encoder->hashPassword($user, 'password'));
            $user->setRole('ROLE_USER');
            $manager->persist($user);

            $this->addReference('user_'. $nbUsers, $user);
        }

        $manager->flush();
    }
}
