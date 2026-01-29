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

namespace Fyennyi\AsyncCache\Bridge\Symfony\DependencyInjection\Compiler;

use Fyennyi\AsyncCache\AsyncCacheManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Compiler pass to register tagged middleware services.
 */
class MiddlewarePass implements CompilerPassInterface
{
    /**
     * Processes the container to find and register tagged middleware.
     *
     * @param ContainerBuilder $container The container builder
     */
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
                /** @var array{priority?: int} $attributes */
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
