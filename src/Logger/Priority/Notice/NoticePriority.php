<?php
declare(strict_types=1);

namespace ExtendsFramework\Logger\Priority\Notice;

use ExtendsFramework\Logger\Priority\PriorityInterface;
use ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class NoticePriority implements PriorityInterface, StaticFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function getValue(): int
    {
        return 5;
    }

    /**
     * @inheritDoc
     */
    public function getKeyword(): string
    {
        return 'notice';
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'Normal but significant conditions.';
    }

    /**
     * @inheritDoc
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        return new NoticePriority();
    }
}
