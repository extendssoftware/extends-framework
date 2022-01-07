<?php
declare(strict_types=1);

namespace ExtendsFramework\ProblemDetails\Framework\ServiceLocator\Loader;

use ExtendsFramework\ProblemDetails\Framework\Http\Middleware\ProblemDetailsMiddleware;
use ExtendsFramework\ProblemDetails\Serializer\Json\JsonSerializer;
use ExtendsFramework\ProblemDetails\Serializer\SerializerInterface;
use ExtendsFramework\ServiceLocator\Resolver\Invokable\InvokableResolver;
use ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class ProblemConfigLoaderTest extends TestCase
{
    /**
     * Load.
     *
     * Test that loader will return correct config.
     *
     * @covers \ExtendsFramework\ProblemDetails\Framework\ServiceLocator\Loader\ProblemDetailsConfigLoader::load()
     */
    public function testProcess(): void
    {
        $loader = new ProblemDetailsConfigLoader();
        $this->assertSame(
            [
                ServiceLocatorInterface::class => [
                    ReflectionResolver::class => [
                        ProblemDetailsMiddleware::class => ProblemDetailsMiddleware::class,
                    ],
                    InvokableResolver::class => [
                        SerializerInterface::class => JsonSerializer::class,
                    ],
                ],
            ],
            $loader->load()
        );
    }
}
