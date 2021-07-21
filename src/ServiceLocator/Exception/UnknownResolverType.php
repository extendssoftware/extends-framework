<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Exception;

use Exception;
use ExtendsFramework\ServiceLocator\ServiceLocatorException;

class UnknownResolverType extends Exception implements ServiceLocatorException
{
    /**
     * Resolver for $type not found.
     *
     * @param string $resolver
     */
    public function __construct(string $resolver)
    {
        parent::__construct(sprintf(
            'Resolver must be instance or subclass of ResolverInterface, got "%s".',
            $resolver
        ));
    }
}
