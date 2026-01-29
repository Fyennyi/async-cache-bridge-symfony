<?php

namespace Fyennyi\AsyncCache\Bridge\Symfony\DependencyInjection\Compiler;

use Fyennyi\AsyncCache\AsyncCacheManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class MiddlewarePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container) : void
    {
        if (! $container->has(AsyncCacheManager::class)) {
            return;
        }

        $definition = $container->findDefinition(AsyncCacheManager::class);
        $taggedServices = $container->findTaggedServiceIds('fyennyi.async_cache.middleware');

        $middlewares = [];
        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $priority = $attributes['priority'] ?? 0;
                $middlewares[$priority][] = new Reference($id);
            }
        }

        if ($middlewares) {
            krsort($middlewares);
            $middlewares = array_merge(...$middlewares);
        }

        $definition->setArgument('$middlewares', $middlewares);
    }
}
