<?php

namespace Tests\Unit\DependencyInjection;

use Fyennyi\AsyncCache\AsyncCacheManager;
use Fyennyi\AsyncCache\Bridge\Symfony\DependencyInjection\AsyncCacheExtension;
use Fyennyi\AsyncCache\Config\AsyncCacheConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AsyncCacheExtensionTest extends TestCase
{
    public function testLoadRegistersServices() : void
    {
        $extension = new AsyncCacheExtension();
        $container = new ContainerBuilder();

        $extension->load([], $container);

        // Check AsyncCacheConfig is registered
        $this->assertTrue($container->hasDefinition('async_cache.config'));
        $configDefinition = $container->getDefinition('async_cache.config');
        $this->assertEquals(AsyncCacheConfig::class, $configDefinition->getClass());

        $configArgs = $configDefinition->getArguments();
        $this->assertArrayHasKey('$cache_adapter', $configArgs);
        $this->assertEquals(new Reference('cache.app'), $configArgs['$cache_adapter']);
        $this->assertArrayHasKey('$logger', $configArgs);
        $this->assertEquals(new Reference('logger'), $configArgs['$logger']);

        // Check AsyncCacheManager is registered
        $this->assertTrue($container->hasDefinition(AsyncCacheManager::class));
        $definition = $container->getDefinition(AsyncCacheManager::class);
        $this->assertTrue($definition->isPublic());

        $arguments = $definition->getArguments();
        $this->assertArrayHasKey('$config', $arguments);
        $this->assertEquals(new Reference('async_cache.config'), $arguments['$config']);
    }
}
