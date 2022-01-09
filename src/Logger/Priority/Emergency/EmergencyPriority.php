<?php
declare(strict_types=1);

namespace ExtendsFramework\Logger\Priority\Emergency;

use ExtendsFramework\Logger\Priority\PriorityInterface;
use ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class EmergencyPriority implements PriorityInterface, StaticFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function getValue(): int
    {
        return 0;
    }

    /**
     * @inheritDoc
     */
    public function getKeyword(): string
    {
        return 'emerg';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'System is unusable.';
    }

    /**
     * @inheritDoc
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        return new EmergencyPriority();
    }
}
