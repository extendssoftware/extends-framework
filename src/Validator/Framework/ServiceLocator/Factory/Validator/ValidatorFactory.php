<?php
declare(strict_types=1);

namespace ExtendsFramework\Validator\Framework\ServiceLocator\Factory\Validator;

use ExtendsFramework\ServiceLocator\Resolver\Factory\ServiceFactoryInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorException;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ExtendsFramework\Validator\ContainerValidator;
use ExtendsFramework\Validator\ValidatorInterface;

class ValidatorFactory implements ServiceFactoryInterface
{
    /**
     * @inheritDoc
     * @throws ServiceLocatorException
     */
    public function createService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        $container = new ContainerValidator();
        foreach ($extra['validators'] ?? [] as $config) {
            $validator = $serviceLocator->getService($config['name'], $config['options'] ?? []);
            if ($validator instanceof ValidatorInterface) {
                $container->addValidator($validator, $config['interrupt'] ?? null);
            }
        }

        return $container;
    }
}
