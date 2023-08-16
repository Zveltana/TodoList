<?php

namespace App\Tests\Controller;

use Exception;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Tests\WebTestHelperTrait;


final class SecurityControllerTest extends WebTestCase
{
    use WebTestHelperTrait;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    /**
     * @throws ReflectionException
     */
    public function testLoginPage()
    {
        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'user+1';
        $form['_password'] = 'password';

        $this->client->submit($form);

        $this->assertResponseRedirects('http://localhost/');
        $this->client->followRedirect();

        self::assertIsAuthenticated(true);

        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @throws ReflectionException
     */
    public function testUserLogin()
    {
        $this->testLoginPage();

        self::assertIsAuthenticated(true);

        $this->client->request('GET', '/login');

        $this->assertResponseRedirects('/');
        $this->client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @throws ReflectionException
     */
    public function testLoginFailPage()
    {
        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'user+150';
        $form['_password'] = 'password';

        $this->client->submit($form);

        $this->assertResponseRedirects('http://localhost/login');
        $this->client->followRedirect();

        self::assertIsAuthenticated(false);

        $this->assertStringContainsString('Identifiants invalides.', $this->client->getResponse()->getContent());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function testLogoutPage()
    {
        $this->testLoginPage();
        $this->client->request('GET', '/logout');

        self::assertIsAuthenticated(false);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }
}
