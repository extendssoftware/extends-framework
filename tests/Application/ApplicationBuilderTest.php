<?php
declare(strict_types=1);

namespace ExtendsFramework\Application;

use ExtendsFramework\Application\Exception\FailedToLoadCache;
use ExtendsFramework\Application\Module\ModuleInterface;
use ExtendsFramework\Application\Module\Provider\ConditionProviderInterface;
use ExtendsFramework\Application\Module\Provider\ConfigProviderInterface;
use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\ServiceLocator\Config\Loader\LoaderInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorFactoryInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use LogicException;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class ApplicationBuilderTest extends TestCase
{
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
        $this->module = new class implements ModuleInterface, ConfigProviderInterface, ConditionProviderInterface {
            /**
             * @var LoaderInterface|null
             */
            protected $loader;

            /**
             * @var bool
             */
            protected $conditioned = false;

            /**
             * @inheritDoc
             */
            public function isConditioned(): bool
            {
                return $this->conditioned;
            }

            /**
             * @inheritDoc
             */
            public function getConfig(): LoaderInterface
            {
                if ($this->loader) {
                    return $this->loader;
                }

                throw new LogicException('Can not load config from conditioned module.');
            }

            /**
             * @param LoaderInterface $loader
             *
             * @return ModuleInterface
             */
            public function setLoader(LoaderInterface $loader): ModuleInterface
            {
                $this->loader = $loader;

                return $this;
            }

            /**
             * @return ModuleInterface
             */
            public function setConditioned(): ModuleInterface
            {
                $this->conditioned = true;

                return $this;
            }
        };
    }

    /**
     * Build.
     *
     * Test that builder will load and cache config and build an instance of ApplicationInterface.
     *
     * @covers \ExtendsFramework\Application\ApplicationBuilder::__construct()
     * @covers \ExtendsFramework\Application\ApplicationBuilder::addConfig()
     * @covers \ExtendsFramework\Application\ApplicationBuilder::addGlobalConfigDirectory()
     * @covers \ExtendsFramework\Application\ApplicationBuilder::setCacheLocation()
     * @covers \ExtendsFramework\Application\ApplicationBuilder::setCacheFilename()
     * @covers \ExtendsFramework\Application\ApplicationBuilder::setCacheEnabled()
     * @covers \ExtendsFramework\Application\ApplicationBuilder::setServiceLocatorFactory()
     * @covers \ExtendsFramework\Application\ApplicationBuilder::addModule()
     * @covers \ExtendsFramework\Application\ApplicationBuilder::build()
     * @covers \ExtendsFramework\Application\ApplicationBuilder::getConfig()
     * @covers \ExtendsFramework\Application\ApplicationBuilder::setFrameworkEnabled()
     * @covers \ExtendsFramework\Application\ApplicationBuilder::reset()
     */
    public function testBuild(): void
    {
        $root = vfsStream::setup('root', null, [
            'config' => [
                'global' => [
                    'global.php' => "<?php return ['foo' => 'bar'];",
                ],
                'local' => [
                    'local.php' => "<?php return ['local' => true];",
                ],
            ],
        ]);

        $loader = $this->createMock(LoaderInterface::class);
        $loader
            ->expects($this->once())
            ->method('load')
            ->willReturn([
                [
                    'enabled' => true,
                ],
            ]);

        $this->module->setLoader($loader);

        /**
         * @var LoaderInterface                $loader
         * @var ServiceLocatorFactoryInterface $factory
         */
        $builder = new ApplicationBuilder();
        $application = $builder
            ->setFrameworkEnabled(true)
            ->addConfig(
                new class implements LoaderInterface {
                    /**
                     * @inheritDoc
                     */
                    public function load(): array
                    {
                        return [
                            'global' => false,
                        ];
                    }
                }
            )
            ->addGlobalConfigDirectory($root->url() . '/config/global', '/(.*)?(local|global)\.php/i')
            ->addGlobalConfigDirectory($root->url() . '/config/local', '/(.*)?(local|global)\.php/i')
            ->setCacheLocation($root->url() . '/config')
            ->setCacheFilename('application.cache')
            ->setCacheEnabled(true)
            ->addModule($this->module)
            ->addModule((clone $this->module)->setConditioned())
            ->build();

        $this->assertIsObject($application);

        $config = include $root->url() . '/config/application.cache.php';

        $this->assertArrayHasKey(ServiceLocatorInterface::class, $config);
        $this->assertArrayHasKey(MiddlewareChainInterface::class, $config);

        $this->assertFalse($config['global']);
        $this->assertSame('bar', $config['foo']);
        $this->assertTrue($config['enabled']);
    }

    /**
     * Cached.
     *
     * Test that cached config is returned.
     *
     * @covers \ExtendsFramework\Application\ApplicationBuilder::__construct()
     * @covers \ExtendsFramework\Application\ApplicationBuilder::setCacheFilename()
     * @covers \ExtendsFramework\Application\ApplicationBuilder::setCacheEnabled()
     * @covers \ExtendsFramework\Application\ApplicationBuilder::setServiceLocatorFactory()
     * @covers \ExtendsFramework\Application\ApplicationBuilder::addModule()
     * @covers \ExtendsFramework\Application\ApplicationBuilder::build()
     * @covers \ExtendsFramework\Application\ApplicationBuilder::getConfig()
     * @covers \ExtendsFramework\Application\ApplicationBuilder::reset()
     */
    public function testCached(): void
    {
        $root = vfsStream::setup('root', null, [
            'config' => [
                'application.cache.php' => "<?php return ['foo' => 'bar'];"
            ],
        ]);

        $loader = $this->createMock(LoaderInterface::class);
        $loader
            ->expects($this->never())
            ->method('load');

        $this->module->setConditioned();
        $this->module->setLoader($loader);

        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->once())
            ->method('getService')
            ->with(ApplicationInterface::class)
            ->willReturn($this->createMock(ApplicationInterface::class));

        $factory = $this->createMock(ServiceLocatorFactoryInterface::class);
        $factory
            ->expects($this->once())
            ->method('create')
            ->willReturn($serviceLocator);

        /**
         * @var ServiceLocatorFactoryInterface $factory
         */
        $builder = new ApplicationBuilder();
        $application = $builder
            ->setCacheLocation($root->url() . '/config')
            ->setCacheEnabled(true)
            ->setCacheFilename('application.cache')
            ->setServiceLocatorFactory($factory)
            ->addModule($this->module)
            ->build();

        $this->assertIsObject($application);
    }

    /**
     * Cache location missing.
     *
     * Test that an exception is thrown when cache location is missing.
     *
     * @covers \ExtendsFramework\Application\ApplicationBuilder::__construct()
     * @covers \ExtendsFramework\Application\ApplicationBuilder::setCacheEnabled()
     * @covers \ExtendsFramework\Application\ApplicationBuilder::build()
     * @covers \ExtendsFramework\Application\ApplicationBuilder::getConfig()
     * @covers \ExtendsFramework\Application\Exception\CacheLocationMissing::__construct
     * @covers \ExtendsFramework\Application\Exception\FailedToLoadCache::__construct
     */
    public function testCacheLocationMissing(): void
    {
        $this->expectException(FailedToLoadCache::class);
        $this->expectExceptionMessage('Failed to load config. See previous exception for more details.');

        $builder = new ApplicationBuilder();
        $builder
            ->setCacheEnabled(true)
            ->build();
    }
}
