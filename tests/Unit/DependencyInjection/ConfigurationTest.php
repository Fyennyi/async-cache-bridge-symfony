<?php

namespace Fyennyi\AsyncCache\Bridge\Symfony\Tests\Unit\DependencyInjection;

use Fyennyi\AsyncCache\Bridge\Symfony\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends TestCase
{
    public function testDefaultConfiguration(): void
    {
        $configuration = new Configuration();
        $processor = new Processor();

        $config = $processor->processConfiguration($configuration, []);

        $this->assertEquals([
            'default_strategy' => 'strict',
        ], $config);
    }

    public function testCustomConfiguration(): void
    {
        $configuration = new Configuration();
        $processor = new Processor();

        $config = $processor->processConfiguration($configuration, [
            'async_cache' => [
                'default_strategy' => 'background',
            ],
        ]);

        $this->assertEquals([
            'default_strategy' => 'background',
        ], $config);
    }
}
