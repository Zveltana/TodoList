<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    private User $user;

    public function setUp(): void
    {
        $this->user = new User();
    }

    public function getUser(): User
    {
        return ($this->user)
        ->setUsername('test')
        ->setPassword('password')
        ->setEmail('test@gmail.com')
        ->setRole('ROLE_USER');
    }

    /**
     * @throws Exception
     */
    public function assertHasErrors(User $user, int $number = 0): void
    {
        self::bootKernel();
        $error = self::getContainer()->get('validator')->validate($user);
        $this->assertCount($number, $error);
    }

    /**
     * @throws Exception
     */
    public function testUserValid()
    {
        $this->assertHasErrors($this->getUser());
    }

    /**
     * @throws Exception
     */
    public function testUsernameNull()
    {
        $user = $this->getUser()->setUsername('');
        $this->assertHasErrors($user, 1);

    }

    /**
     * @throws Exception
     */
    public function testUsernameExist()
    {
        $user = $this->user->setUsername('test');
        $this->assertHasErrors($user, 1);
    }

    /**
     * @throws Exception
     */
    public function testEmailValid()
    {
        $user = $this->getUser()->setEmail('gdgfgfg');
        $this->assertHasErrors($user, 1);
    }

    /**
     * @throws Exception
     */
    public function testEmailExist()
    {
        $user = $this->user->setEmail('test@gmail.com');
        $this->assertHasErrors($user, 1);
    }
}