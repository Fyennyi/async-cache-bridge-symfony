<?php

namespace Fyennyi\AsyncCache\Bridge\Symfony;

use Fyennyi\AsyncCache\Bridge\Symfony\DependencyInjection\Compiler\MiddlewarePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Symfony Bundle for AsyncCache integration.
 */
class AsyncCacheBundle extends Bundle
{
    /**
     * Builds the bundle.
     *
     * @param ContainerBuilder $container The container builder
     */
    public function build(ContainerBuilder $container) : void
    {
        parent::build($container);

        $container->addCompilerPass(new MiddlewarePass());
    }
}
