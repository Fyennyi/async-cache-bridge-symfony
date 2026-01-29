<?php

namespace Fyennyi\AsyncCache\Bridge\Symfony\DependencyInjection;

use Fyennyi\AsyncCache\AsyncCacheManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;

class AsyncCacheExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container) : void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $definition = new Definition(AsyncCacheManager::class, [
            new Reference('cache.app'),
            null,
            new Reference('logger'),
            new Reference('lock.factory'),
            [],
            new Reference('event_dispatcher'),
            null,
            null,
        ]);

        $definition->setPublic(true);
        $container->setDefinition(AsyncCacheManager::class, $definition);
    }
}
