<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\Reflection\Exception;

use Exception;
use ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolverException;
use ReflectionNamedType;
use ReflectionParameter;

class InvalidParameter extends Exception implements ReflectionResolverException
{
    /**
     * Unsupported type for parameter.
     *
     * @param ReflectionParameter $parameter
     */
    public function __construct(ReflectionParameter $parameter)
    {
        /** @var ReflectionNamedType $type */
        $type = $parameter->getType();

        parent::__construct(sprintf(
            'Reflection parameter "%s" must be a class, got type "%s".',
            $parameter->getName(),
            $type->getNAme()
        ));
    }
}
