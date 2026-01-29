<?php

namespace Tests\Unit\DependencyInjection\Compiler;

use Fyennyi\AsyncCache\AsyncCacheManager;
use Fyennyi\AsyncCache\Bridge\Symfony\DependencyInjection\Compiler\MiddlewarePass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class MiddlewarePassTest extends TestCase
{
    public function testProcessRegistersMiddlewares() : void
    {
        $container = new ContainerBuilder();
        $managerDefinition = new Definition(AsyncCacheManager::class);
        $container->setDefinition(AsyncCacheManager::class, $managerDefinition);

        $middlewareDefinition = new Definition();
        $middlewareDefinition->addTag('fyennyi.async_cache.middleware', ['priority' => 10]);
        $container->setDefinition('my_middleware', $middlewareDefinition);

        $pass = new MiddlewarePass();
        $pass->process($container);

        $arguments = $managerDefinition->getArguments();
        $this->assertArrayHasKey('$middlewares', $arguments);

        $middlewares = $arguments['$middlewares'];
        $this->assertCount(1, $middlewares);
        $this->assertEquals(new Reference('my_middleware'), $middlewares[0]);
    }

    public function testProcessDoesNothingIfManagerMissing() : void
    {
        $container = new ContainerBuilder();
        $pass = new MiddlewarePass();

        $pass->process($container);

        $this->assertFalse($container->hasDefinition(AsyncCacheManager::class));
    }
}
