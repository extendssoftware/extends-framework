<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator;

use ExtendsFramework\ServiceLocator\Exception\ServiceNotFound;
use ExtendsFramework\ServiceLocator\Resolver\ResolverInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

class ServiceLocatorTest extends TestCase
{
    /**
     * Register.
     *
     * Test that a resolver can be registered.
     *
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::__construct()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::addResolver()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::getService()
     */
    public function testRegister(): void
    {
        $resolver = $this->createMock(ResolverInterface::class);
        $resolver
            ->expects($this->once())
            ->method('getService')
            ->with('A')
            ->willReturn(new stdClass());

        $resolver
            ->expects($this->once())
            ->method('hasService')
            ->with('A')
            ->willReturn(true);

        /**
         * @var ResolverInterface $resolver
         */
        $serviceLocator = new ServiceLocator([]);
        $service = $serviceLocator
            ->addResolver($resolver, 'invokables')
            ->getService('A');

        $this->assertInstanceOf(stdClass::class, $service);
    }

    /**
     * Shared service.
     *
     * Test that a shared service will be returned and cached by the service locator.
     *
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::__construct()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::addResolver()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::getService()
     */
    public function testSharedService(): void
    {
        $resolver = $this->createMock(ResolverInterface::class);
        $resolver
            ->expects($this->once())
            ->method('getService')
            ->with('A')
            ->willReturn(new stdClass());

        $resolver
            ->expects($this->once())
            ->method('hasService')
            ->with('A')
            ->willReturn(true);

        /**
         * @var ResolverInterface $resolver
         */
        $serviceLocator = new ServiceLocator([]);
        $service1 = $serviceLocator
            ->addResolver($resolver, 'invokables')
            ->getService('A');

        $service2 = $serviceLocator
            ->addResolver($resolver, 'invokables')
            ->getService('A');

        $this->assertSame($service1, $service2);
    }

    /**
     * Managed service.
     *
     * Test that a managed service will be returned and not cached by the service locator.
     *
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::__construct()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::addResolver()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::getService()
     */
    public function testManagedService(): void
    {
        $resolver = $this->createMock(ResolverInterface::class);
        $resolver
            ->expects($this->exactly(2))
            ->method('getService')
            ->with(
                'A',
                $this->isInstanceOf(ServiceLocatorInterface::class),
                ['foo' => 'bar']
            )
            ->willReturnOnConsecutiveCalls(
                new stdClass(),
                new stdClass()
            );

        $resolver
            ->expects($this->exactly(2))
            ->method('hasService')
            ->with('A')
            ->willReturn(true);

        /**
         * @var ResolverInterface $resolver
         */
        $serviceLocator = new ServiceLocator([]);
        $service1 = $serviceLocator
            ->addResolver($resolver, 'invokables')
            ->getService('A', ['foo' => 'bar']);

        $service2 = $serviceLocator
            ->addResolver($resolver, 'invokables')
            ->getService('A', ['foo' => 'bar']);

        $this->assertNotSame($service1, $service2);
    }

    /**
     * Get config.
     *
     * Test that config is returned.
     *
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::__construct()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::getConfig()
     */
    public function testGetConfig(): void
    {
        $serviceLocator = new ServiceLocator([
            'foo' => 'bar',
        ]);

        $this->assertSame(['foo' => 'bar'], $serviceLocator->getConfig());
    }

    /**
     * Is console.
     *
     * Test that console is the current environment.
     *
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::__construct()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::isConsole()
     */
    public function testIsConsole(): void
    {
        $serviceLocator = new ServiceLocator([]);

        $this->assertTrue($serviceLocator->isConsole());
    }

    /**
     * Service not found.
     *
     * Test that a service can not be located and an exception will be thrown.
     *
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::__construct()
     * @covers \ExtendsFramework\ServiceLocator\ServiceLocator::getService()
     * @covers \ExtendsFramework\ServiceLocator\Exception\ServiceNotFound::__construct()
     */
    public function testServiceNotFound(): void
    {
        $this->expectException(ServiceNotFound::class);
        $this->expectExceptionMessage('No service found for key "foo".');

        $serviceLocator = new ServiceLocator([]);
        $serviceLocator->getService('foo');
    }
}
