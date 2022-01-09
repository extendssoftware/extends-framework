<?php
declare(strict_types=1);

namespace ExtendsFramework\Logger\Priority\Warning;

use ExtendsFramework\Logger\Priority\PriorityInterface;
use ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class WarningPriority implements PriorityInterface, StaticFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function getValue(): int
    {
        return 4;
    }

    /**
     * @inheritDoc
     */
    public function getKeyword(): string
    {
        return 'warning';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'Warning conditions.';
    }

    /**
     * @inheritDoc
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        return new WarningPriority();
    }
}
