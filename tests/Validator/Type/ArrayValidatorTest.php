<?php
declare(strict_types=1);

namespace ExtendsFramework\Validator\Type;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ExtendsFramework\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class ArrayValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that boolean value '[]' is an array.
     *
     * @covers \ExtendsFramework\Validator\Type\ArrayValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new ArrayValidator();
        $result = $validator->validate([]);

        $this->assertTrue($result->isValid());
    }

    /**
     * Invalid.
     *
     * Test that int value '1' is not an array.
     *
     * @covers \ExtendsFramework\Validator\Type\ArrayValidator::validate()
     * @covers \ExtendsFramework\Validator\Type\ArrayValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new ArrayValidator();
        $result = $validator->validate(1);

        $this->assertFalse($result->isValid());
    }

    /**
     * Factory.
     *
     * Test that factory returns a ArrayValidator.
     *
     * @covers \ExtendsFramework\Validator\Type\ArrayValidator::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $validator = ArrayValidator::factory(ArrayValidator::class, $serviceLocator);

        $this->assertInstanceOf(ValidatorInterface::class, $validator);
    }
}
