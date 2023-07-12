<?php

namespace App\Services\Task;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ToggleTask extends AbstractController implements ToggleTaskInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function toggle(Task $task): bool
    {
        $isDone = $task->isDone();
        $task->setDone(!$isDone);
        $this->entityManager->flush();

        return !$isDone;
    }
}