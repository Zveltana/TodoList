<?php

namespace App\Services\Task;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;

readonly class DeleteTask implements DeleteTaskInterface
{
    /**
     * Constructor for the class.
     *
     * @param EntityManagerInterface $entityManager The entity manager to be used by the class.
     */
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function delete(Task $task): Task
    {
        $this->entityManager->remove($task);
        $this->entityManager->flush();

        return $task;
    }
}