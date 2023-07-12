<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLoginPage()
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testLogoutPage()
    {
        $client = static::createClient();
        $client->request('GET', '/logout');

        $this->assertEquals(405, $client->getResponse()->getStatusCode());
        $this->assertResponseRedirects('/login');
    }
}
