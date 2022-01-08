<?php
declare(strict_types=1);

namespace ExtendsFramework\Logger\Priority\Critical;

use ExtendsFramework\Logger\Priority\PriorityInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class CriticalPriorityTest extends TestCase
{
    /**
     * Get methods.
     *
     * Test that get methods will return correct values.
     *
     * @covers \ExtendsFramework\Logger\Priority\Critical\CriticalPriority::getValue()
     * @covers \ExtendsFramework\Logger\Priority\Critical\CriticalPriority::getKeyword()
     * @covers \ExtendsFramework\Logger\Priority\Critical\CriticalPriority::getDescription()
     */
    public function testGetMethods(): void
    {
        $priority = new CriticalPriority();

        $this->assertSame(2, $priority->getValue());
        $this->assertSame('crit', $priority->getKeyword());
        $this->assertSame('Critical conditions, such as hard device errors.', $priority->getDescription());
    }

    /**
     * Factory.
     *
     * Test that factory methods returns an instanceof PriorityInterface.
     *
     * @covers \ExtendsFramework\Logger\Priority\Critical\CriticalPriority::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $priority = CriticalPriority::factory('AbstractPriority', $serviceLocator, []);

        $this->assertInstanceOf(PriorityInterface::class, $priority);
    }
}
