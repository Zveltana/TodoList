<?php

namespace App\Services\Task;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DeleteTask extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function delete(Task $task): Task
    {
        $this->denyAccessUnlessGranted('delete', $task);
        $this->entityManager->remove($task);
        $this->entityManager->flush();

        return $task;
    }
}