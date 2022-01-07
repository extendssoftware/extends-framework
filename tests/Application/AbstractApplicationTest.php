<?php
declare(strict_types=1);

namespace ExtendsFramework\Application;

use ExtendsFramework\Application\Module\ModuleInterface;
use ExtendsFramework\Application\Module\Provider\ShutdownProviderInterface;
use ExtendsFramework\Application\Module\Provider\StartupProviderInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class AbstractApplicationTest extends TestCase
{
    /**
     * Dummy abstract application.
     *
     * @var AbstractApplication
     */
    private $application;

    /**
     * Dummy module.
     *
     * @var ModuleInterface
     */
    private $module;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->module = new class implements ModuleInterface, StartupProviderInterface, ShutdownProviderInterface {
            /**
             * @var bool
             */
            protected $startup = false;

            /**
             * @var bool
             */
            protected $shutdown = false;

            /**
             * @inheritDoc
             */
            public function onStartup(ServiceLocatorInterface $serviceLocator): void
            {
                $this->startup = true;
            }

            /**
             * @inheritDoc
             */
            public function onShutdown(ServiceLocatorInterface $serviceLocator): void
            {
                $this->shutdown = true;
            }

            /**
             * @return bool
             */
            public function isStartup(): bool
            {
                return $this->startup;
            }

            /**
             * @return bool
             */
            public function isShutdown(): bool
            {
                return $this->shutdown;
            }
        };

        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        $this->application = new class($serviceLocator, [$this->module]) extends AbstractApplication {
            /**
             * @inheritDoc
             */
            protected function run(): AbstractApplication
            {
                return $this;
            }
        };
    }


    /**
     * Bootstrap.
     *
     * Test that modules will be bootstrapped by application.
     *
     * @covers \ExtendsFramework\Application\AbstractApplication::__construct()
     * @covers \ExtendsFramework\Application\AbstractApplication::bootstrap()
     * @covers \ExtendsFramework\Application\AbstractApplication::getServiceLocator()
     */
    public function testBootstrap(): void
    {
        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $this->application->bootstrap();

        $this->assertTrue($this->module->isStartup());
        $this->assertTrue($this->module->isShutdown());
    }
}
