<?php
declare(strict_types=1);

namespace ExtendsFramework\Validator\Type;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ExtendsFramework\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

class ObjectValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that stdClass is an valid object.
     *
     * @covers \ExtendsFramework\Validator\Type\ObjectValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new ObjectValidator();
        $result = $validator->validate(new stdClass());

        $this->assertTrue($result->isValid());
    }

    /**
     * Invalid.
     *
     * Test that string value 'foo' is not an object.
     *
     * @covers \ExtendsFramework\Validator\Type\ObjectValidator::validate()
     * @covers \ExtendsFramework\Validator\Type\ObjectValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new ObjectValidator();
        $result = $validator->validate('foo');

        $this->assertFalse($result->isValid());
    }

    /**
     * Factory.
     *
     * Test that factory returns a ObjectValidator.
     *
     * @covers \ExtendsFramework\Validator\Type\ObjectValidator::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $validator = ObjectValidator::factory(ObjectValidator::class, $serviceLocator);

        $this->assertInstanceOf(ValidatorInterface::class, $validator);
    }
}
