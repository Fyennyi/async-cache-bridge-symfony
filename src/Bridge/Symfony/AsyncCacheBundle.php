<?php

namespace Fyennyi\AsyncCache\Bridge\Symfony;

class AsyncCacheBundle
{
    public function getContainerExtension()
    {
        if (class_exists('Fyennyi\\AsyncCache\\Bridge\\Symfony\\DependencyInjection\\AsyncCacheExtension')) {
            return new \Fyennyi\AsyncCache\Bridge\Symfony\DependencyInjection\AsyncCacheExtension();
        }
        return null;
    }
}
