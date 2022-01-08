<?php
declare(strict_types=1);

namespace ExtendsFramework\Logger\Priority\Debug;

use ExtendsFramework\Logger\Priority\PriorityInterface;
use ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class DebugPriority implements PriorityInterface, StaticFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function getValue(): int
    {
        return 7;
    }

    /**
     * @inheritDoc
     */
    public function getKeyword(): string
    {
        return 'debug';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'Debug-level messages.';
    }

    /**
     * @inheritDoc
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        return new DebugPriority();
    }
}
