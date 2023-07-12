<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testTaskListPage()
    {
        $client = static::createClient();
        $client->request('GET', '/tasks');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Task List');
    }

    public function testCreateTask()
    {
        $client = static::createClient();

        $user = new User();
        $client->loginUser($user);

        $client->request('GET', '/tasks/create');

        $this->assertResponseIsSuccessful();

        $form = $client->getCrawler()->selectButton('Save')->form();
        $form['task[title]'] = 'Test Task';
        $form['task[description]'] = 'This is a test task.';

        $client->submit($form);
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.alert-success', 'The task has been successfully added.');
    }

    public function testToggleTask()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/tasks');

        $this->assertResponseIsSuccessful();

        $toggleLink = $crawler->filter('.task-toggle-link')->first()->link();
        $client->click($toggleLink);
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.alert-success', 'The task has been marked as done.');

        // Toggle back to unfinished
        $crawler = $client->request('GET', '/tasks');
        $toggleLink = $crawler->filter('.task-toggle-link')->first()->link();
        $client->click($toggleLink);
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.alert-success', 'The task has been marked as unfinished.');
    }

    public function testDeleteTask()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/tasks');

        $this->assertResponseIsSuccessful();

        $deleteLink = $crawler->filter('.task-delete-link')->first()->link();
        $client->click($deleteLink);
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.alert-success', 'The task has been successfully deleted.');
    }
}
