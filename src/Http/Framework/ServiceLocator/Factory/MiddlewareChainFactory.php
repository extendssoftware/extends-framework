<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Framework\ServiceLocator\Factory;

use ExtendsFramework\Http\Middleware\Chain\MiddlewareChain;
use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\Http\Middleware\MiddlewareInterface;
use ExtendsFramework\ServiceLocator\Resolver\Factory\ServiceFactoryInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorException;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class MiddlewareChainFactory implements ServiceFactoryInterface
{
    /**
     * @inheritDoc
     * @throws ServiceLocatorException
     */
    public function createService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        $config = $serviceLocator->getConfig();
        $config = $config[MiddlewareChainInterface::class] ?? [];

        $chain = new MiddlewareChain();
        foreach ($config as $middlewareKey => $priority) {
            $middleware = $serviceLocator->getService($middlewareKey);
            if ($middleware instanceof MiddlewareInterface) {
                $chain->addMiddleware($middleware, $priority);
            }
        }

        return $chain;
    }
}
