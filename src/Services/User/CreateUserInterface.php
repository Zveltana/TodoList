<?php

namespace App\Services\User;

use App\Entity\User;

interface CreateUserInterface
{
    public function create(User $user): User;
}