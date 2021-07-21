<?php
declare(strict_types=1);

namespace ExtendsFramework\Logger\Priority;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class AbstractPriorityTest extends TestCase
{
    /**
     * Dummy abstract priority.
     *
     * @var AbstractPriority
     */
    private $priority;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->priority = new class extends AbstractPriority
        {
            /**
             * @inheritDoc
             */
            public function getValue(): int
            {
                return 0;
            }

            /**
             * @inheritDoc
             */
            public function getKeyword(): string
            {
                return '';
            }

            /**
             * @inheritDoc
             */
            public function getDescription(): string
            {
                return '';
            }
        };
    }

    /**
     * Factory.
     *
     * Test that factory methods returns an instanceof PriorityInterface.
     *
     * @covers \ExtendsFramework\Logger\Priority\AbstractPriority::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $priority = $this->priority::factory('AbstractPriority', $serviceLocator, []);

        $this->assertInstanceOf(PriorityInterface::class, $priority);
    }
}
