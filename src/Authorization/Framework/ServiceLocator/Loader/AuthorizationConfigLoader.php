<?php
declare(strict_types=1);

namespace ExtendsFramework\Authorization\Framework\ServiceLocator\Loader;

use ExtendsFramework\Authorization\AuthorizerInterface;
use ExtendsFramework\Authorization\Framework\ServiceLocator\Factory\AuthorizerFactory;
use ExtendsFramework\ServiceLocator\Config\Loader\LoaderInterface;
use ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class AuthorizationConfigLoader implements LoaderInterface
{
    /**
     * @inheritDoc
     */
    public function load(): array
    {
        return [
            ServiceLocatorInterface::class => [
                FactoryResolver::class => [
                    AuthorizerInterface::class => AuthorizerFactory::class,
                ],
            ],
            AuthorizerInterface::class => [
                'realms' => [],
            ],
        ];
    }
}
