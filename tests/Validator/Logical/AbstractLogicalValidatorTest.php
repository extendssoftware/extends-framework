<?php
declare(strict_types=1);

namespace ExtendsFramework\Validator\Logical;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ExtendsFramework\Validator\Result\ResultInterface;
use ExtendsFramework\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class AbstractLogicalValidatorTest extends TestCase
{
    /**
     * Dummy abstract logical validator.
     *
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->validator = new class extends AbstractLogicalValidator {
            /**
             * @inheritDoc
             */
            protected function getTemplates(): array
            {
                return [];
            }

            /**
             * @inheritDoc
             */
            public function validate($value, $context = null): ResultInterface
            {
                return $this->getValidResult();
            }
        };
    }

    /**
     * Factory.
     *
     * Test that factory returns a AbstractLogicalValidator.
     *
     * @covers \ExtendsFramework\Validator\Logical\AbstractLogicalValidator::factory()
     * @covers \ExtendsFramework\Validator\Logical\AbstractLogicalValidator::__construct()
     * @covers \ExtendsFramework\Validator\Logical\AbstractLogicalValidator::addValidator()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->once())
            ->method('getService')
            ->with(
                ValidatorInterface::class,
                [
                    'foo' => 'bar',
                ]
            )
            ->willReturn($this->createMock(ValidatorInterface::class));

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $validator = $this->validator::factory(ValidatorInterface::class, $serviceLocator, [
            'validators' => [
                [
                    'name' => ValidatorInterface::class,
                    'options' => [
                        'foo' => 'bar',
                    ],
                ],
            ],
        ]);

        $this->assertInstanceOf(ValidatorInterface::class, $validator);
    }
}
