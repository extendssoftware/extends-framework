<?php
declare(strict_types=1);

namespace ExtendsFramework\Validator\Type;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ExtendsFramework\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class FloatValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that float value '9.0' is a float.
     *
     * @covers \ExtendsFramework\Validator\Type\FloatValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new FloatValidator();
        $result = $validator->validate(9.1);

        $this->assertTrue($result->isValid());
    }

    /**
     * Invalid.
     *
     * Test that int value '9' is not a float.
     *
     * @covers \ExtendsFramework\Validator\Type\FloatValidator::validate()
     * @covers \ExtendsFramework\Validator\Type\FloatValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new FloatValidator();
        $result = $validator->validate(9);

        $this->assertFalse($result->isValid());
    }

    /**
     * Factory.
     *
     * Test that factory returns a FloatValidator.
     *
     * @covers \ExtendsFramework\Validator\Type\FloatValidator::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $validator = FloatValidator::factory(FloatValidator::class, $serviceLocator);

        $this->assertInstanceOf(ValidatorInterface::class, $validator);
    }
}
