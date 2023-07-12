<?php

namespace App\Services\Task;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CreateTask extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function create(Task $task): Task
    {
        /* @var User $user */
        $user = $this->getUser();
        $task->setAuthor($user);
        $task->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }
}