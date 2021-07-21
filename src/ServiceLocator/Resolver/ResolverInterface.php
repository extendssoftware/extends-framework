<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver;

use ExtendsFramework\ServiceLocator\ServiceLocatorException;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

interface ResolverInterface
{
    /**
     * Check if resolver can resolve service for $key.
     *
     * @param string $key
     *
     * @return bool
     */
    public function hasService(string $key): bool;

    /**
     * Get service for $key.
     *
     * @param string                  $key
     * @param ServiceLocatorInterface $serviceLocator
     * @param array|null              $extra
     *
     * @return object
     * @throws ResolverException
     * @throws ServiceLocatorException
     */
    public function getService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object;

    /**
     * Create new resolver from $config.
     *
     * @param array $services
     *
     * @return ResolverInterface
     */
    public static function factory(array $services): ResolverInterface;
}
