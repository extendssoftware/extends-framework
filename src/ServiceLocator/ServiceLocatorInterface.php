<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator;

interface ServiceLocatorInterface
{
    /**
     * Get a service with the name key.
     *
     * A shared service will be created when extra is null. If not, a managed service will be created. An exception
     * will be thrown when no service is found for key or service is an non object.
     *
     * @param string       $key
     * @param mixed[]|null $extra
     *
     * @return object
     * @throws ServiceLocatorException
     */
    public function getService(string $key, array $extra = null): object;

    /**
     * Get global config.
     *
     * @return mixed[]
     */
    public function getConfig(): array;

    /**
     * If console is the current environment.
     *
     * @return bool
     */
    public function isConsole(): bool;
}
