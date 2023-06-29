<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($nbrTask = 1; $nbrTask <= 10; $nbrTask++) {
            $task = new Task();

            $task->setTitle($faker->text());
            $task->setContent($faker->paragraph());
            $task->setIsDone($faker->boolean());
            $task->setCreatedAt(new \DateTimeImmutable());
            $task->setAuthor($this->getReference('user_' . $faker->numberBetween(1, 16)));

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
