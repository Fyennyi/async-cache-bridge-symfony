<?php

/*
 *
 *     _                          ____           _            ____  _   _ ____
 *    / \   ___ _   _ _ __   ___ / ___|__ _  ___| |__   ___  |  _ \| | | |  _ \
 *   / _ \ / __| | | | '_ \ / __| |   / _` |/ __| '_ \ / _ \ | |_) | |_| | |_) |
 *  / ___ \\__ \ |_| | | | | (__| |__| (_| | (__| | | |  __/ |  __/|  _  |  __/
 * /_/   \_\___/\__, |_| |_|\___|\____\__,_|\___|_| |_|\___| |_|   |_| |_|_|
 *              |___/
 *
 * This program is free software: you can redistribute and/or modify
 * it under the terms of the CSSM Unlimited License v2.0.
 *
 * This license permits unlimited use, modification, and distribution
 * for any purpose while maintaining authorship attribution.
 *
 * The software is provided "as is" without warranty of any kind.
 *
 * @author Serhii Cherneha
 * @link https://chernega.eu.org/
 *
 *
 */

namespace Fyennyi\AsyncCache\Bridge\Symfony\DependencyInjection;

use Fyennyi\AsyncCache\AsyncCacheManager;
use Fyennyi\AsyncCache\Config\AsyncCacheConfig;
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

        // Register AsyncCacheConfig
        $configDefinition = new Definition(AsyncCacheConfig::class);
        $configDefinition->setArguments([
            '$cache_adapter' => new Reference('cache.app'),
            '$logger'        => new Reference('logger'),
            '$lock_factory'  => new Reference('lock.factory'),
            '$dispatcher'    => new Reference('event_dispatcher'),
        ]);
        $container->setDefinition('async_cache.config', $configDefinition);

        // Register AsyncCacheManager
        $definition = new Definition(AsyncCacheManager::class);
        $definition->setArguments([
            '$config' => new Reference('async_cache.config'),
        ]);
        $definition->setPublic(true);
        $container->setDefinition(AsyncCacheManager::class, $definition);
    }
}
