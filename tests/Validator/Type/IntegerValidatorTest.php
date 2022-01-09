<?php
declare(strict_types=1);

namespace ExtendsFramework\Validator\Type;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ExtendsFramework\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class IntegerValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that integer value '9' is an valid integer.
     *
     * @covers \ExtendsFramework\Validator\Type\IntegerValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new IntegerValidator();
        $result = $validator->validate(9);

        $this->assertTrue($result->isValid());
    }

    /**
     * Invalid.
     *
     * Test that string value 'foo' is an valid integer.
     *
     * @covers \ExtendsFramework\Validator\Type\IntegerValidator::validate()
     * @covers \ExtendsFramework\Validator\Type\IntegerValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new IntegerValidator();
        $result = $validator->validate('foo');

        $this->assertFalse($result->isValid());
    }

    /**
     * Factory.
     *
     * Test that factory returns a IntegerValidator.
     *
     * @covers \ExtendsFramework\Validator\Type\IntegerValidator::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $validator = IntegerValidator::factory(IntegerValidator::class, $serviceLocator);

        $this->assertInstanceOf(ValidatorInterface::class, $validator);
    }
}
