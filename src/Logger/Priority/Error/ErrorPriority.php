<?php
declare(strict_types=1);

namespace ExtendsFramework\Logger\Priority\Error;

use ExtendsFramework\Logger\Priority\PriorityInterface;
use ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class ErrorPriority implements PriorityInterface, StaticFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function getValue(): int
    {
        return 3;
    }

    /**
     * @inheritDoc
     */
    public function getKeyword(): string
    {
        return 'err';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'Error conditions.';
    }

    /**
     * @inheritDoc
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        return new ErrorPriority();
    }
}
