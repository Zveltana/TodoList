<?php

namespace App\Tests\Controller;

use App\Tests\WebTestHelperTrait;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    use WebTestHelperTrait;

    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * @throws ReflectionException
     */
    public function loginAdmin(): void
    {
        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'user+1';
        $form['_password'] = 'password';

        $this->client->submit($form);

        self::assertIsAuthenticated(true);
    }

    /**
     * @throws ReflectionException
     */
    public function loginUser(): void
    {
        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'user+10';
        $form['_password'] = 'password';

        $this->client->submit($form);

        self::assertIsAuthenticated(true);
    }

    /**
     * @throws ReflectionException
     */
    public function logout(): void
    {
        $this->client->request('GET', '/logout');

        self::assertIsAuthenticated(false);
    }

    /**
     * @throws ReflectionException
     */
    public function testUserConnected()
    {
        $this->loginUser();
        $this->client->request('GET', '/');
        self::assertIsAuthenticated(true);

        $this->logout();

        self::assertIsAuthenticated(false);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @throws ReflectionException
     */
    public function testTaskListPage()
   {
       $this->loginUser();
       $this->client->request('GET', '/tasks');

       $this->assertResponseIsSuccessful();
       $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
   }

    /**
     * @throws ReflectionException
     */
    public function testCreateTask()
   {
       $this->loginAdmin();
       $crawler = $this->client->request('POST', '/tasks/create');

       $form = $crawler->selectButton('Ajouter')->form();
       $form['task[title]'] = 'Je test les tests';
       $form['task[content]'] = 'Je test le contenu';

       $this->client->submit($form);

       $this->assertResponseRedirects('/tasks');
       $this->client->followRedirect();

       $this->assertResponseIsSuccessful();
       $this->assertSelectorTextContains('.alert-success', 'Superbe ! La tâche a été bien été ajoutée.');
       $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
   }

    /**
     * @throws ReflectionException
     */
    public function testTaskFinished()
   {
       $this->loginUser();
       $this->client->request('GET', '/tasks/finished');

       $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
   }

    /**
     * @throws ReflectionException
     */
    public function testTaskUnfinished()
    {
        $this->loginUser();
        $this->client->request('GET', '/tasks/unfinished');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @throws ReflectionException
     */
    public function testToggleTask()
   {
       $this->loginUser();
       $crawler = $this->client->request('GET', '/tasks');

       $this->assertGreaterThan(0, $crawler->filter('.thumbnail')->count());

       $buttonTextDone = 'Marquer comme faite';
       $button = $crawler->filterXPath("//button[contains(text(), '$buttonTextDone')]")->first();
       $form = $button->form();
       $this->client->submit($form);

       // Vérifier la réponse de redirection
       $this->assertResponseRedirects('/tasks');
       $this->client->followRedirect();

       // Effectuer les assertions nécessaires
       $this->assertResponseIsSuccessful();
       $this->assertSelectorTextContains('.alert-success', 'a bien été marquée comme faite.');

       $buttonTextNotDone = 'Marquer non terminée';
       $button = $crawler->filterXPath("//button[contains(text(), '$buttonTextNotDone')]")->first();
       $form = $button->form();
       $this->client->submit($form);

       // Vérifier la réponse de redirection
       $this->assertResponseRedirects('/tasks');
       $this->client->followRedirect();

       // Effectuer les assertions nécessaires
       $this->assertResponseIsSuccessful();
       $this->assertSelectorTextContains('.alert-success', 'a bien été marquée comme non terminée.');
   }

    /**
     * @throws ReflectionException
     */
    public function testEditTask()
    {
        $this->loginUser();
        $crawler = $this->client->request('POST', '/tasks/200/edit');

        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = 'Faire à manger';
        $form['task[content]'] = 'Dîner de ce soir : escalope à la milanaise';
        $this->client->submit($form);

        $this->assertResponseRedirects('/tasks');
        $this->client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.alert-success', 'Superbe ! La tâche a bien été modifiée.');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @throws ReflectionException
     */
    public function testDeleteTaskAnonymous()
   {
       $this->loginUser();
       $this->client->request('DELETE', '/tasks/203/delete');
       $this->assertResponseStatusCodeSame(403);

       $this->logout();

       $this->loginAdmin();
       $crawler = $this->client->request('DELETE', '/tasks/203/delete');

       $crawler->selectButton('Supprimer');

       $this->assertResponseRedirects('/tasks');
       $this->client->followRedirect();

       $this->assertResponseIsSuccessful();
       $this->assertSelectorTextContains('.alert-success', 'Superbe ! La tâche a bien été supprimée.');
   }
}
