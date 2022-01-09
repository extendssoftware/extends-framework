<?php
declare(strict_types=1);

namespace ExtendsFramework\Authentication\Framework\ServiceLocator\Factory;

use ExtendsFramework\Authentication\Authenticator;
use ExtendsFramework\Authentication\AuthenticatorInterface;
use ExtendsFramework\Authentication\Realm\RealmInterface;
use ExtendsFramework\ServiceLocator\Resolver\Factory\ServiceFactoryInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorException;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class AuthenticatorFactory implements ServiceFactoryInterface
{
    /**
     * @inheritDoc
     * @throws ServiceLocatorException
     */
    public function createService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        $config = $serviceLocator->getConfig();
        $config = $config[AuthenticatorInterface::class] ?? [];

        $authenticator = new Authenticator();
        foreach ($config['realms'] ?? [] as $config) {
            $realm = $serviceLocator->getService($config['name'], $config['options'] ?? []);
            if ($realm instanceof RealmInterface) {
                $authenticator->addRealm($realm);
            }
        }

        return $authenticator;
    }
}
