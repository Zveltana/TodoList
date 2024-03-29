<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($nbrTask = 1; $nbrTask <= 7; $nbrTask++) {
            $task = new Task();

            $task->setTitle($faker->text());
            $task->setContent($faker->paragraph());
            $task->setDone($faker->boolean());
            $task->setCreatedAt(new \DateTimeImmutable());
            /** @var User $user */
            $user = $this->getReference('user_' . $faker->numberBetween(1, 10));
            $task->setAuthor($user);

            $manager->persist($task);
        }

        for ($nbrTask = 8; $nbrTask <= 10; $nbrTask++) {
            $task = new Task();

            $task->setTitle($faker->text());
            $task->setContent($faker->paragraph());
            $task->setDone($faker->boolean());
            $task->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($task);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class
        ];
    }
}
