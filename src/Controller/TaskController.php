<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Services\Task\CreateTaskInterface;
use App\Services\Task\DeleteTaskInterface;
use App\Services\Task\ToggleTaskInterface;
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

    /**
     * Create a new task.
     *
     * This method handles the creation of a new task using the provided form data.
     *
     * @param Request             $request    The HTTP request object.
     * @param CreateTaskInterface $createTask The service responsible for creating the task.
     *
     * @return Response The HTTP response object containing the result of the action.
     */
    #[Route('/tasks/create', name: 'task_create')]
    public function create(Request $request, CreateTaskInterface $createTask): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $createTask->create($task);

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
    public function toggle(Task $task, ToggleTaskInterface $toggleTask): Response
    {
        $isDone = $toggleTask->toggle($task);

        if ($isDone === true) {
            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));
        } else {
            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme non terminée.', $task->getTitle()));
        }

        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks/{id}/delete', name: 'task_delete')]
    public function delete(Task $task, DeleteTaskInterface $deleteTask): Response
    {
        $this->denyAccessUnlessGranted('delete', $task);

        $deleteTask->delete($task);

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
