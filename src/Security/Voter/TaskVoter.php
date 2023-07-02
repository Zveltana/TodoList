<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use App\Entity\Task;
use App\Entity\User;

class TaskVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return $attribute === 'delete' && $subject instanceof Task;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // L'utilisateur n'est pas connecté
            return false;
        }

        /** @var Task $task */
        $task = $subject;

        if ($task->getAuthor() === $user) {
            // L'utilisateur est l'auteur de la tâche, donc il peut la supprimer
            return true;
        }

        if ($task->getAuthor() === null && $user->getRole() === 'ROLE_ADMIN') {
            // La tâche a un auteur anonyme et l'utilisateur est un administrateur, donc il peut la supprimer
            return true;
        }

        return false;
    }
}
