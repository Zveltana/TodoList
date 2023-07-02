<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Security\Voter\TaskVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    #[Route('/tasks', name: 'task_list')]
    public function list(TaskRepository $taskRepository): Response
    {
        $task = $taskRepository->findAll();

        return $this->render('task/list.html.twig', [
            'tasks' => $task,
        ]);
    }

    #[Route('/tasks/create', name: 'task_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /* @var User $user */
            $user = $this->getUser();
            $task->setAuthor($user);
            $task->setCreatedAt(new \DateTimeImmutable());
            $em = $entityManager;

            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->renderForm('task/create.html.twig', ['form' => $form]);
    }

    #[Route('/tasks/finished', name: 'task_finished')]
    public function done(TaskRepository $taskRepository): Response
    {
        $taskFinished = $taskRepository->findBy(['done' => 1]);

        return $this->render('task/finished.html.twig', [
            'tasksFinished' => $taskFinished,
        ]);
    }

    #[Route('/tasks/unfinished', name: 'task_unfinished')]
    public function unfinished(TaskRepository $taskRepository): Response
    {
        $taskUnfinished = $taskRepository->findBy(['done' => 0]);

        return $this->render('task/unfinished.html.twig', [
            'tasksUnfinished' => $taskUnfinished,
        ]);
    }

    #[Route('/tasks/{id}/edit', name: 'task_edit')]
    public function edit(Task $task, Request $request, TaskRepository $taskRepository): Response
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $taskRepository->save($task, true);

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->renderForm('task/edit.html.twig', [
            'form' => $form,
            'task' => $task,
        ]);
    }

    #[Route('/tasks/{id}/toggle', name: 'task_toggle')]
    public function toggle(Task $task, EntityManagerInterface $em): Response
    {
        if ($task->isDone() !== true) {
            $task->setDone(true);
            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));
        } else {
            $task->setDone(false);
            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme non terminée.', $task->getTitle()));
        }

        $em->flush();

        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks/{id}/delete', name: 'task_delete')]
    public function delete(Task $task, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('delete', $task);
        $em->remove($task);
        $em->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
