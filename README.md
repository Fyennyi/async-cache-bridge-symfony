# Async Cache Symfony Bridge

[![Latest Stable Version](https://img.shields.io/packagist/v/fyennyi/async-cache-bridge-symfony.svg?label=Packagist&logo=packagist)](https://packagist.org/packages/fyennyi/async-cache-bridge-symfony)
[![Total Downloads](https://img.shields.io/packagist/dt/fyennyi/async-cache-bridge-symfony.svg?label=Downloads&logo=packagist)](https://packagist.org/packages/fyennyi/async-cache-bridge-symfony)
[![License](https://img.shields.io/packagist/l/fyennyi/async-cache-bridge-symfony.svg?label=Licence&logo=open-source-initiative)](https://packagist.org/packages/fyennyi/async-cache-bridge-symfony)
[![Tests](https://img.shields.io/github/actions/workflow/status/Fyennyi/async-cache-php/phpunit.yml?label=Tests&logo=github)](https://github.com/Fyennyi/async-cache-php/actions/workflows/phpunit.yml)

This is a **Symfony Bridge** for the [Async Cache PHP](https://github.com/Fyennyi/async-cache-php) library. It integrates the asynchronous caching manager directly into your Symfony application, automatically wiring it with the default Symfony Cache, Lock, and Logger components.

## Features

- **Automatic Service Registration**: Registers `AsyncCacheManager` as a service in the container.
- **Seamless Integration**: Automatically injects:
  - `cache.app` (Your default Symfony cache pool).
  - `lock.factory` (Symfony Lock component for atomic operations).
  - `logger` (Monolog integration).
  - `event_dispatcher`.
- **Configuration Friendly**: Allows defining global strategies via `yaml` configuration.

## Installation

Run the following command in your terminal:

```bash
composer require fyennyi/async-cache-bridge-symfony
```

If you are not using Symfony Flex, you may need to register the bundle manually in `config/bundles.php`:

```php
// config/bundles.php
return [
    // ...
    Fyennyi\AsyncCache\Bridge\Symfony\AsyncCacheBundle::class => ['all' => true],
];
```

## Configuration

You can configure the default behavior of the cache manager in `config/packages/async_cache.yaml`.

```yaml
# config/packages/async_cache.yaml
async_cache:
    # Set the default strategy for cache misses (strict, background, etc.)
    # Default is 'strict'
    default_strategy: strict 
```

## Usage

### Injecting the Manager

The bridge registers the `Fyennyi\AsyncCache\AsyncCacheManager` class in the service container. You can use dependency injection to access it in your Controllers or Services.

```php
namespace App\Service;

use Fyennyi\AsyncCache\AsyncCacheManager;
use Fyennyi\AsyncCache\CacheOptions;

class WeatherService
{
    public function __construct(
        private AsyncCacheManager $cache
    ) {}

    public function getForecast(string $city): \React\Promise\PromiseInterface
    {
        return $this->cache->wrap(
            'weather_' . $city,
            fn() => $this->fetchFromApi($city),
            new CacheOptions(ttl: 300)
        );
    }

    private function fetchFromApi(string $city) { /* ... */ }
}
```

### Requirements

Since this bridge automatically wires Symfony components, ensure your environment has the necessary services configured:
- **Cache**: A configured `cache.app` pool (standard in Symfony).
- **Lock**: The `symfony/lock` component (recommended for atomic operations).

```bash
composer require symfony/lock
```

## Contributing

Contributions are welcome! Please follow these steps:

1. Fork the project.
2. Create your feature branch (`git checkout -b feature/AmazingFeature`).
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`).
4. Push to the branch (`git push origin feature/AmazingFeature`).
5. Open a Pull Request.

## License

This library is licensed under the CSSM Unlimited License v2.0 (CSSM-ULv2). See the [LICENSE](LICENSE) file for details.
