<?php

namespace Tests\Unit;

use Fyennyi\AsyncCache\Bridge\Symfony\AsyncCacheBundle;
use Fyennyi\AsyncCache\Bridge\Symfony\DependencyInjection\Compiler\MiddlewarePass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AsyncCacheBundleTest extends TestCase
{
    public function testBuildRegistersMiddlewarePass() : void
    {
        $bundle = new AsyncCacheBundle();
        $container = $this->createMock(ContainerBuilder::class);

        $container->expects($this->once())
            ->method('addCompilerPass')
            ->with($this->isInstanceOf(MiddlewarePass::class));

        $bundle->build($container);
    }
}
