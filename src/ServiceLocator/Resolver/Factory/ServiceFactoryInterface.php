<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\Factory;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

interface ServiceFactoryInterface
{
    /**
     * Create a service for key.
     *
     * @param string                  $key
     * @param ServiceLocatorInterface $serviceLocator
     * @param mixed[]|null            $extra
     *
     * @return object
     * @throws ServiceFactoryException
     */
    public function createService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object;
}
