<?php

namespace Fyennyi\AsyncCache\Bridge\Symfony\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration tree for the AsyncCacheBundle.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder The configuration tree builder
     */
    public function getConfigTreeBuilder() : TreeBuilder
    {
        $tree_builder = new TreeBuilder('async_cache');
        $root_node = $tree_builder->getRootNode();

        $root_node
            ->children()
                ->scalarNode('default_strategy')
                    ->defaultValue('strict')
                ->end()
            ->end();

        return $tree_builder;
    }
}
