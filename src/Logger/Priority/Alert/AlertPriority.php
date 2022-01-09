<?php
declare(strict_types=1);

namespace ExtendsFramework\Logger\Priority\Alert;

use ExtendsFramework\Logger\Priority\PriorityInterface;
use ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class AlertPriority implements PriorityInterface, StaticFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function getValue(): int
    {
        return 1;
    }

    /**
     * @inheritDoc
     */
    public function getKeyword(): string
    {
        return 'alert';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'Action must be taken immediately.';
    }

    /**
     * @inheritDoc
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        return new AlertPriority();
    }
}
