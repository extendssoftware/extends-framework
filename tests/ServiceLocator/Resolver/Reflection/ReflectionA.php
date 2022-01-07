<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\Reflection;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class ReflectionA
{
    /**
     * ClassA constructor.
     *
     * @param ReflectionB             $b
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function __construct(ReflectionB $b, ServiceLocatorInterface $serviceLocator)
    {
    }
}
