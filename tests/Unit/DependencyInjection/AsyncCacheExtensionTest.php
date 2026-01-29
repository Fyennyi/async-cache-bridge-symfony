<?php

namespace Fyennyi\AsyncCache\Bridge\Symfony\Tests\Unit\DependencyInjection;

use Fyennyi\AsyncCache\AsyncCacheManager;
use Fyennyi\AsyncCache\Bridge\Symfony\DependencyInjection\AsyncCacheExtension;
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

        $this->assertTrue($container->hasDefinition(AsyncCacheManager::class));

        $definition = $container->getDefinition(AsyncCacheManager::class);
        $this->assertTrue($definition->isPublic());

        $arguments = $definition->getArguments();
        $this->assertArrayHasKey('$cache_adapter', $arguments);
        $this->assertEquals(new Reference('cache.app'), $arguments['$cache_adapter']);

        $this->assertArrayHasKey('$logger', $arguments);
        $this->assertEquals(new Reference('logger'), $arguments['$logger']);
    }
}
