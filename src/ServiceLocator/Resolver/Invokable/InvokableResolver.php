<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Resolver\Invokable;

use ExtendsFramework\ServiceLocator\Resolver\Invokable\Exception\NonExistingClass;
use ExtendsFramework\ServiceLocator\Resolver\ResolverInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class InvokableResolver implements ResolverInterface
{
    /**
     * An associative array which holds the invokables.
     *
     * @var mixed[]
     */
    private array $invokables = [];

    /**
     * @inheritDoc
     */
    public static function factory(array $services): ResolverInterface
    {
        $resolver = new InvokableResolver();
        foreach ($services as $key => $invokable) {
            $resolver->addInvokable($key, $invokable);
        }

        return $resolver;
    }

    /**
     * @inheritDoc
     */
    public function hasService(string $key): bool
    {
        return isset($this->invokables[$key]);
    }

    /**
     * An exception will be thrown when invokable is not an existing class.
     *
     * @inheritDoc
     */
    public function getService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        $invokable = $this->invokables[$key];
        if (!class_exists($invokable)) {
            throw new NonExistingClass($invokable);
        }

        return new $invokable();
    }

    /**
     * Register invokable for key.
     *
     * @param string $key
     * @param string $invokable
     *
     * @return InvokableResolver
     */
    public function addInvokable(string $key, string $invokable): InvokableResolver
    {
        $this->invokables[$key] = $invokable;

        return $this;
    }
}
