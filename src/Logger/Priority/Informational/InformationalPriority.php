<?php
declare(strict_types=1);

namespace ExtendsFramework\Logger\Priority\Informational;

use ExtendsFramework\Logger\Priority\PriorityInterface;
use ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class InformationalPriority implements PriorityInterface, StaticFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function getValue(): int
    {
        return 6;
    }

    /**
     * @inheritDoc
     */
    public function getKeyword(): string
    {
        return 'info';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'Informational messages.';
    }

    /**
     * @inheritDoc
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        return new InformationalPriority();
    }
}
