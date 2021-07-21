<?php
declare(strict_types=1);

namespace ExtendsFramework\Application\Framework\ServiceLocator\Loader;

use ExtendsFramework\Application\ApplicationInterface;
use ExtendsFramework\Application\Framework\Http\Middleware\InternalServerErrorMiddleware;
use ExtendsFramework\Application\Framework\Http\Middleware\NotImplementedMiddleware;
use ExtendsFramework\Application\Framework\Http\Middleware\RendererMiddleware;
use ExtendsFramework\Application\Framework\ServiceLocator\Factory\ApplicationFactory;
use ExtendsFramework\Application\Http\Renderer\Renderer;
use ExtendsFramework\Application\Http\Renderer\RendererInterface;
use ExtendsFramework\Hateoas\Framework\Http\Middleware\Hateoas\HateoasMiddleware;
use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\Logger\Framework\Http\Middleware\Logger\LoggerMiddleware;
use ExtendsFramework\ProblemDetails\Framework\Http\Middleware\ProblemDetailsMiddleware;
use ExtendsFramework\Router\Framework\Http\Middleware\Controller\ControllerMiddleware;
use ExtendsFramework\Router\Framework\Http\Middleware\Router\RouterMiddleware;
use ExtendsFramework\Security\Framework\Http\Middleware\AuthenticationMiddleware;
use ExtendsFramework\Security\Framework\Http\Middleware\AuthorizationMiddleware;
use ExtendsFramework\ServiceLocator\Config\Loader\LoaderInterface;
use ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver;
use ExtendsFramework\ServiceLocator\Resolver\Invokable\InvokableResolver;
use ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class ApplicationConfigLoader implements LoaderInterface
{
    /**
     * @inheritDoc
     */
    public function load(): array
    {
        return [
            ServiceLocatorInterface::class => [
                FactoryResolver::class => [
                    ApplicationInterface::class => ApplicationFactory::class,
                ],
                InvokableResolver::class => [
                    NotImplementedMiddleware::class => NotImplementedMiddleware::class,
                    InternalServerErrorMiddleware::class => InternalServerErrorMiddleware::class,
                    RendererInterface::class => Renderer::class,
                ],
                ReflectionResolver::class => [
                    RendererMiddleware::class => RendererMiddleware::class,
                ],
            ],
            MiddlewareChainInterface::class => [
                RendererMiddleware::class => 1000,
                ProblemDetailsMiddleware::class => 900,
                InternalServerErrorMiddleware::class => 800,
                LoggerMiddleware::class => 700,
                HateoasMiddleware::class => 600,
                RouterMiddleware::class => 500,
                AuthenticationMiddleware::class => 400,
                AuthorizationMiddleware::class => 300,
                ControllerMiddleware::class => 200,
                NotImplementedMiddleware::class => 100,
            ],
        ];
    }
}
