<?php
declare(strict_types=1);

namespace ExtendsFramework\Validator\Logical;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ExtendsFramework\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class NotValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that int '0' is false.
     *
     * @covers \ExtendsFramework\Validator\Logical\NotValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new NotValidator();
        $result = $validator->validate(0);

        $this->assertTrue($result->isValid());
    }

    /**
     * Invalid.
     *
     * Test that int '1' is not false.
     *
     * @covers \ExtendsFramework\Validator\Logical\NotValidator::validate()
     * @covers \ExtendsFramework\Validator\Logical\NotValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new NotValidator();
        $result = $validator->validate(1);

        $this->assertFalse($result->isValid());
    }

    /**
     * Factory.
     *
     * Test that factory returns a NotValidator.
     *
     * @covers \ExtendsFramework\Validator\Logical\NotValidator::factory()
     * @covers \ExtendsFramework\Validator\Logical\NotValidator::__construct()
     * @covers \ExtendsFramework\Validator\Logical\NotValidator::addValidator()
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
        $validator = NotValidator::factory(ValidatorInterface::class, $serviceLocator, [
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
