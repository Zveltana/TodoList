<?php

namespace App\Services\User;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class CreateUser implements CreateUserInterface
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher, private EntityManagerInterface $entityManager)
    {
    }

    public function create(User $user): User
    {
        $password = $this->passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($password);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}