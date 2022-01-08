<?php
declare(strict_types=1);

namespace ExtendsFramework\Validator\Type;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ExtendsFramework\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class BooleanValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that boolean value 'true' is a boolean.
     *
     * @covers \ExtendsFramework\Validator\Type\BooleanValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new BooleanValidator();
        $result = $validator->validate(true);

        $this->assertTrue($result->isValid());
    }

    /**
     * Invalid.
     *
     * Test that int value '1' is not a boolean.
     *
     * @covers \ExtendsFramework\Validator\Type\BooleanValidator::validate()
     * @covers \ExtendsFramework\Validator\Type\BooleanValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new BooleanValidator();
        $result = $validator->validate(1);

        $this->assertFalse($result->isValid());
    }

    /**
     * Factory.
     *
     * Test that factory returns a BooleanValidator.
     *
     * @covers \ExtendsFramework\Validator\Type\BooleanValidator::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $validator = BooleanValidator::factory(BooleanValidator::class, $serviceLocator);

        $this->assertInstanceOf(ValidatorInterface::class, $validator);
    }
}
