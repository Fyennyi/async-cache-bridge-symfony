<?php

namespace Fyennyi\AsyncCache\Bridge\Symfony\Tests\Integration;

use Fyennyi\AsyncCache\AsyncCacheManager;
use Fyennyi\AsyncCache\Bridge\Symfony\AsyncCacheBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

class BundleInitializationTest extends TestCase
{
    public function testBundleBootsAndRegistersService() : void
    {
        $kernel = new AsyncCacheTestKernel('test', true);
        $kernel->boot();

        $container = $kernel->getContainer();

        // Check if service exists
        $this->assertTrue($container->has(AsyncCacheManager::class));

        // Check if service can be instantiated
        $service = $container->get(AsyncCacheManager::class);
        $this->assertInstanceOf(AsyncCacheManager::class, $service);
    }
}

class AsyncCacheTestKernel extends Kernel
{
    public function registerBundles() : iterable
    {
        return [
            new AsyncCacheBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader) : void
    {
        $loader->load(function (ContainerBuilder $container) {
            // Register required dependencies
            $container->register('cache.inner_adapter', ArrayAdapter::class);
            $container->register('cache.app', \Symfony\Component\Cache\Psr16Cache::class)
                 ->setArgument(0, new \Symfony\Component\DependencyInjection\Reference('cache.inner_adapter'));

            $container->register('logger', \Psr\Log\NullLogger::class);

            $container->register('lock.store', \Symfony\Component\Lock\Store\FlockStore::class);
            $container->register('lock.factory', \Symfony\Component\Lock\LockFactory::class)
                ->setArgument(0, new \Symfony\Component\DependencyInjection\Reference('lock.store'));

            $container->register('event_dispatcher', \Symfony\Component\EventDispatcher\EventDispatcher::class);
        });
    }

    public function getCacheDir() : string
    {
        return sys_get_temp_dir() . '/async_cache_test/cache';
    }

    public function getLogDir() : string
    {
        return sys_get_temp_dir() . '/async_cache_test/logs';
    }
}
