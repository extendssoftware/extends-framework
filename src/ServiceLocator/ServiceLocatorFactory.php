<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator;

use ExtendsFramework\ServiceLocator\Exception\UnknownResolverType;
use ExtendsFramework\ServiceLocator\Resolver\ResolverInterface;

class ServiceLocatorFactory implements ServiceLocatorFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function create(array $config): ServiceLocatorInterface
    {
        $serviceLocator = new ServiceLocator($config);
        foreach ($config[ServiceLocatorInterface::class] ?? [] as $fqcn => $services) {
            if (!is_string($fqcn) || !is_subclass_of($fqcn, ResolverInterface::class, true)) {
                throw new UnknownResolverType($fqcn);
            }

            $resolver = $fqcn::factory($services);
            if ($resolver instanceof ResolverInterface) {
                $serviceLocator->addResolver($resolver, $fqcn);
            }
        }

        return $serviceLocator;
    }
}
