<?php
declare(strict_types=1);

namespace ExtendsFramework\Logger\Framework\ServiceLocator\Factory\Logger;

use ExtendsFramework\Logger\Logger;
use ExtendsFramework\Logger\LoggerInterface;
use ExtendsFramework\Logger\Writer\WriterInterface;
use ExtendsFramework\ServiceLocator\Resolver\Factory\ServiceFactoryInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorException;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class LoggerFactory implements ServiceFactoryInterface
{
    /**
     * @inheritDoc
     * @throws ServiceLocatorException
     */
    public function createService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        $config = $serviceLocator->getConfig();
        $config = $config[LoggerInterface::class];

        $logger = new Logger();
        foreach ($config['writers'] ?? [] as $config) {
            $writer = $serviceLocator->getService($config['name'], $config['options'] ?? []);
            if ($writer instanceof WriterInterface) {
                $logger->addWriter($writer);
            }
        }

        return $logger;
    }
}
