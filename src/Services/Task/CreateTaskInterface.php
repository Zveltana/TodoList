<?php

namespace App\Services\Task;

use App\Entity\Task;

interface CreateTaskInterface
{
    public function create(Task $task): Task;
}