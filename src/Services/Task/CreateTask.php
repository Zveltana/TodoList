<?php

namespace App\Services\Task;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

readonly class CreateTask implements CreateTaskInterface
{
    public function __construct(private EntityManagerInterface $entityManager, private Security $security)
    {
    }

    public function create(Task $task): Task
    {
        /* @var User $user */
        $user = $this->security->getUser();
        $task->setAuthor($user);
        $task->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }
}