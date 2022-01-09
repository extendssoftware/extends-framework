<?php
declare(strict_types=1);

namespace ExtendsFramework\Logger\Priority\Warning;

use ExtendsFramework\Logger\Priority\PriorityInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class WarningPriorityTest extends TestCase
{
    /**
     * Get methods.
     *
     * Test that get methods will return correct values.
     *
     * @covers \ExtendsFramework\Logger\Priority\Warning\WarningPriority::getValue()
     * @covers \ExtendsFramework\Logger\Priority\Warning\WarningPriority::getKeyword()
     * @covers \ExtendsFramework\Logger\Priority\Warning\WarningPriority::getDescription()
     */
    public function testGetMethods(): void
    {
        $priority = new WarningPriority();

        $this->assertSame(4, $priority->getValue());
        $this->assertSame('warning', $priority->getKeyword());
        $this->assertSame('Warning conditions.', $priority->getDescription());
    }

    /**
     * Factory.
     *
     * Test that factory methods returns an instanceof PriorityInterface.
     *
     * @covers \ExtendsFramework\Logger\Priority\Warning\WarningPriority::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $priority = WarningPriority::factory('AbstractPriority', $serviceLocator, []);

        $this->assertInstanceOf(PriorityInterface::class, $priority);
    }
}
