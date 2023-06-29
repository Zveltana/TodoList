<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        $roles = ['ROLE_USER', 'ROLE_ADMIN'];

        for ($nbUsers = 1; $nbUsers <= 10; $nbUsers++) {
            $user = new User();

            $user->setUsername(sprintf('user+%d', $nbUsers));
            $user->setEmail(sprintf('user+%d@gmail.com', $nbUsers));
            $user->setPassword($this->encoder->hashPassword($user, 'password'));
            $user->setRoles($faker->randomElements($roles));
            $manager->persist($user);

            $this->addReference('user_'. $nbUsers, $user);
        }

        for ($nbUsers = 11; $nbUsers <= 16; $nbUsers++) {
            $user = new User();

            $user->setUsername('anonyme');
            $user->setEmail('anonyme');
            $user->setPassword($this->encoder->hashPassword($user, 'password'));
            $manager->persist($user);

            $this->addReference('user_'. $nbUsers, $user);
        }

        $manager->flush();
    }
}
