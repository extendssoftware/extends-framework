<?php
declare(strict_types=1);

namespace ExtendsFramework\Logger\Priority\Critical;

use ExtendsFramework\Logger\Priority\PriorityInterface;
use ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class CriticalPriority implements PriorityInterface, StaticFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function getValue(): int
    {
        return 2;
    }

    /**
     * @inheritDoc
     */
    public function getKeyword(): string
    {
        return 'crit';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'Critical conditions, such as hard device errors.';
    }

    /**
     * @inheritDoc
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        return new CriticalPriority();
    }
}
