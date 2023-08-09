<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
{
    private Task $task;

    public function setUp(): void
    {
        $this->task = new Task();
    }

    public function getTask(): Task
    {
        return ($this->task)
            ->setTitle('test')
            ->setContent('Un test de tâche')
            ->setCreatedAt(new \DateTimeImmutable());
    }

    /**
     * @throws Exception
     */
    public function assertHasErrors(Task $task, int $number = 0): void
    {
        self::bootKernel();
        $error = self::getContainer()->get('validator')->validate($task);
        $this->assertCount($number, $error);
    }

    /**
     * @throws Exception
     */
    public function testTaskValid()
    {
        $this->assertHasErrors($this->getTask());
    }

    /**
     * @throws Exception
     */
    public function testTitleNull()
    {
        $task = $this->getTask()->setTitle('');
        $this->assertHasErrors($task, 1);
    }

    /**
     * @throws Exception
     */
    public function testContentNull()
    {
        $task = $this->getTask()->setContent('');
        $this->assertHasErrors($task, 1);
    }
}