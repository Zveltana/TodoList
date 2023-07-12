<?php

declare(strict_types=1);

namespace App\Tests;

use ReflectionException;
use ReflectionMethod;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\SecurityBundle\DataCollector\SecurityDataCollector;
use Symfony\Component\HttpKernel\Profiler\Profile;

trait WebTestHelperTrait
{
    /**
     * @throws ReflectionException
     */
    private static function getClient(): KernelBrowser
    {
        $method = new ReflectionMethod(WebTestCase::class, 'getClient');

        /** @var KernelBrowser $kernel */
        $kernel = $method->invoke(null);

        return $kernel;
    }

    /**
     * @throws ReflectionException
     */
    public static function assertIsAuthenticated(bool $isAuthenticated): void
    {
        /** @var Profile $profile */
        $profile = self::getClient()->getProfile();
        $collector = $profile->getCollector('security');
        self::assertInstanceOf(SecurityDataCollector::class, $collector);
        self::assertSame($isAuthenticated, $collector->isAuthenticated());
    }
}