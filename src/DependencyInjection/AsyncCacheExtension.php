<?php

namespace Fyennyi\AsyncCache\Bridge\Symfony\DependencyInjection;

use Fyennyi\AsyncCache\AsyncCacheManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Dependency injection extension for Symfony.
 */
class AsyncCacheExtension extends Extension
{
    /**
     * Loads the configuration and registers the AsyncCacheManager service.
     *
     * @param array<array-key, mixed> $configs   The configuration array
     * @param ContainerBuilder        $container The container builder
     */
    public function load(array $configs, ContainerBuilder $container) : void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $definition = new Definition(AsyncCacheManager::class);

        $definition->setArguments([
            '$cache_adapter' => new Reference('cache.app'),
            '$logger'        => new Reference('logger'),
            '$lock_factory'  => new Reference('lock.factory'),
            '$dispatcher'    => new Reference('event_dispatcher'),
        ]);

        $definition->setPublic(true);
        $container->setDefinition(AsyncCacheManager::class, $definition);
    }
}
