<?php
declare(strict_types=1);

namespace ExtendsFramework\Authorization\Framework\ServiceLocator\Factory;

use ExtendsFramework\Authorization\Realm\RealmInterface;
use ExtendsFramework\Authorization\AuthorizerInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class AuthorizerFactoryTest extends TestCase
{
    /**
     * Create service.
     *
     * Test that instance of AuthorizerInterface will be created.
     *
     * @covers \ExtendsFramework\Authorization\Framework\ServiceLocator\Factory\AuthorizerFactory::createService()
     */
    public function testCreateService(): void
    {
        $realm = $this->createMock(RealmInterface::class);

        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->once())
            ->method('getConfig')
            ->willReturn([
                AuthorizerInterface::class => [
                    'realms' => [
                        [
                            'name' => RealmInterface::class,
                            'options' => [
                                'foo' => 'bar',
                            ],
                        ],
                    ],
                ],
            ]);

        $serviceLocator
            ->expects($this->once())
            ->method('getService')
            ->with(RealmInterface::class, ['foo' => 'bar'])
            ->willReturn($realm);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $factory = new AuthorizerFactory();
        $authenticator = $factory->createService(AuthorizerInterface::class, $serviceLocator);

        $this->assertInstanceOf(AuthorizerInterface::class, $authenticator);
    }
}
