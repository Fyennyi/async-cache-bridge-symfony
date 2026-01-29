<?php

namespace Fyennyi\AsyncCache\Bridge\Symfony\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder() : TreeBuilder
    {
        $treeBuilder = new TreeBuilder('async_cache');
        $rootNode = method_exists($treeBuilder, 'getRootNode') ? $treeBuilder->getRootNode() : $treeBuilder->root('async_cache');

        $rootNode
            ->children()
                ->scalarNode('default_strategy')->defaultValue('strict')->end()
            ->end();

        return $treeBuilder;
    }
}
