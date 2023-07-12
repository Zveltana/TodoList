<?php

namespace App\Services\Task;

use App\Entity\Task;

interface ToggleTaskInterface
{
    public function toggle(Task $task): bool;
}