<?php
declare(strict_types=1);

namespace ExtendsFramework\Logger\Priority\Notice;

use ExtendsFramework\Logger\Priority\PriorityInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class NoticePriorityTest extends TestCase
{
    /**
     * Get methods.
     *
     * Test that get methods will return correct values.
     *
     * @covers \ExtendsFramework\Logger\Priority\Notice\NoticePriority::getValue()
     * @covers \ExtendsFramework\Logger\Priority\Notice\NoticePriority::getKeyword()
     * @covers \ExtendsFramework\Logger\Priority\Notice\NoticePriority::getDescription()
     */
    public function testGetMethods(): void
    {
        $priority = new NoticePriority();

        $this->assertSame(5, $priority->getValue());
        $this->assertSame('notice', $priority->getKeyword());
        $this->assertSame('Normal but significant conditions.', $priority->getDescription());
    }

    /**
     * Factory.
     *
     * Test that factory methods returns an instanceof PriorityInterface.
     *
     * @covers \ExtendsFramework\Logger\Priority\Notice\NoticePriority::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $priority = NoticePriority::factory('AbstractPriority', $serviceLocator, []);

        $this->assertInstanceOf(PriorityInterface::class, $priority);
    }
}
