<?php
declare(strict_types=1);

namespace ExtendsFramework\Validator\Comparison;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ExtendsFramework\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class IdenticalValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that string '1' is identical to string '1'.
     *
     * @covers \ExtendsFramework\Validator\Comparison\IdenticalValidator::__construct()
     * @covers \ExtendsFramework\Validator\Comparison\IdenticalValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new IdenticalValidator(1);
        $result = $validator->validate(1);

        $this->assertTrue($result->isValid());
    }

    /**
     * Invalid.
     *
     * Test that string '1' is not identical to int '1'.
     *
     * @covers \ExtendsFramework\Validator\Comparison\IdenticalValidator::__construct()
     * @covers \ExtendsFramework\Validator\Comparison\IdenticalValidator::validate()
     * @covers \ExtendsFramework\Validator\Comparison\IdenticalValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new IdenticalValidator(1);
        $result = $validator->validate(1.0);

        $this->assertFalse($result->isValid());
    }

    /**
     * Factory.
     *
     * Test that factory returns a IdenticalValidator.
     *
     * @covers \ExtendsFramework\Validator\Comparison\IdenticalValidator::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $validator = IdenticalValidator::factory(ValidatorInterface::class, $serviceLocator, [
            'subject' => 5.5,
        ]);

        $this->assertInstanceOf(ValidatorInterface::class, $validator);
    }
}
