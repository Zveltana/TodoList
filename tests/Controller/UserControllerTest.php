<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private \Symfony\Bundle\FrameworkBundle\KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function loginAdmin(): void
    {
        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'user+1';
        $form['_password'] = 'password';

        $this->client->submit($form);
    }

    public function loginUser(): void
    {
        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'user+10';
        $form['_password'] = 'password';

        $this->client->submit($form);
    }

    public function logout(): void
    {
        $this->client->request('GET', '/logout');
    }

   public function testIndex(): void
   {
       $this->loginUser();
       $this->client->request('GET', '/users');
       $this->assertResponseStatusCodeSame(403);

       $this->logout();

       $this->loginAdmin();
       $this->client->request('GET', '/users');
       $this->assertResponseIsSuccessful();
       $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

   public function testCreateUser(): void
   {
       $this->loginUser();
       $this->client->request('POST', '/users/create');
       $this->assertResponseStatusCodeSame(403);

       $this->logout();

       $this->loginAdmin();
       $crawler = $this->client->request('GET', '/users/create');

       $form = $crawler->selectButton('Ajouter')->form();
       $form['user[username]'] = 'Arthur';
       $form['user[email]'] = 'arthur@gmail.com';
       $form['user[password][first]'] = 'password';
       $form['user[password][second]'] = 'password';
       $form['user[role]'] = 'ROLE_USER';

       $this->client->submit($form);

       $this->assertResponseRedirects('/users');
       $this->client->followRedirect();

       $this->assertResponseIsSuccessful();
       $this->assertSelectorTextContains('.alert-success', 'Superbe ! L\'utilisateur a bien été ajouté.');
       $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
   }

   public function testEditUser(): void
   {
       $this->loginUser();
       $this->client->request('GET', '/users/113/edit');
       $this->assertResponseStatusCodeSame(403);

       $this->logout();

       $this->loginAdmin();
       $crawler = $this->client->request('POST', '/users/113/edit');

       $form = $crawler->selectButton('Modifier')->form();
       $form['user[username]'] = 'Bob';
       $form['user[email]'] = 'bob@gmail.com';
       $form['user[role]'] = 'ROLE_ADMIN';
       $this->client->submit($form);

       $this->assertResponseRedirects('/users');
       $this->client->followRedirect();

       $this->assertResponseIsSuccessful();
       $this->assertSelectorTextContains('.alert-success', 'Superbe ! L\'utilisateur a bien été modifié');
       $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
   }
}
