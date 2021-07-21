<?php
declare(strict_types=1);

namespace ExtendsFramework\ProblemDetails\Framework\ServiceLocator\Loader;

use ExtendsFramework\ProblemDetails\Framework\Http\Middleware\ProblemDetailsMiddleware;
use ExtendsFramework\ProblemDetails\Serializer\Json\JsonSerializer;
use ExtendsFramework\ProblemDetails\Serializer\SerializerInterface;
use ExtendsFramework\ServiceLocator\Config\Loader\LoaderInterface;
use ExtendsFramework\ServiceLocator\Resolver\Invokable\InvokableResolver;
use ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class ProblemDetailsConfigLoader implements LoaderInterface
{
    /**
     * @inheritDoc
     */
    public function load(): array
    {
        return [
            ServiceLocatorInterface::class => [
                ReflectionResolver::class => [
                    ProblemDetailsMiddleware::class => ProblemDetailsMiddleware::class,
                ],
                InvokableResolver::class => [
                    SerializerInterface::class => JsonSerializer::class,
                ],
            ],
        ];
    }
}
