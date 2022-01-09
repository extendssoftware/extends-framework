<?php
declare(strict_types=1);

namespace ExtendsFramework\Application\Framework\ServiceLocator\Factory;

use ExtendsFramework\Application\Console\ConsoleApplication;
use ExtendsFramework\Application\Http\HttpApplication;
use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\Http\Request\RequestInterface;
use ExtendsFramework\ServiceLocator\Resolver\Factory\ServiceFactoryInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorException;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ExtendsFramework\Shell\ShellInterface;

class ApplicationFactory implements ServiceFactoryInterface
{
    /**
     * @inheritDoc
     * @throws ServiceLocatorException
     */
    public function createService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        if ($extra['console'] ?? false) {
            return $this->getConsoleApplication($serviceLocator, $extra);
        }

        return $this->getHttpApplication($serviceLocator, $extra);
    }

    /**
     * Get console application.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param mixed[]|null            $extra
     *
     * @return ConsoleApplication
     * @throws ServiceLocatorException
     */
    private function getConsoleApplication(
        ServiceLocatorInterface $serviceLocator,
        array $extra = null
    ): ConsoleApplication {
        /** @var ShellInterface $shell */
        $shell = $serviceLocator->getService(ShellInterface::class);

        return new ConsoleApplication($shell, $serviceLocator, $extra['modules'] ?? []);
    }

    /**
     * Get HTTP application.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param mixed[]|null            $extra
     *
     * @return HttpApplication
     * @throws ServiceLocatorException
     */
    private function getHttpApplication(ServiceLocatorInterface $serviceLocator, array $extra = null): HttpApplication
    {
        /** @var MiddlewareChainInterface $middlewareChain */
        $middlewareChain = $serviceLocator->getService(MiddlewareChainInterface::class);

        /** @var RequestInterface $request */
        $request = $serviceLocator->getService(RequestInterface::class);

        return new HttpApplication($middlewareChain, $request, $serviceLocator, $extra['modules'] ?? []);
    }
}
