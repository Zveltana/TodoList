<?php

namespace App\Services\Task;

use App\Entity\Task;

interface DeleteTaskInterface
{
    public function delete(Task $task): Task;
}