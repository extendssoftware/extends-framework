<?php
declare(strict_types=1);

namespace ExtendsFramework\Validator\Comparison;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ExtendsFramework\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class LessThanValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that int '1' is less than int '2'.
     *
     * @covers \ExtendsFramework\Validator\Comparison\LessThanValidator::__construct()
     * @covers \ExtendsFramework\Validator\Comparison\LessThanValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new LessThanValidator(2);
        $result = $validator->validate(1);

        $this->assertTrue($result->isValid());
    }

    /**
     * Invalid.
     *
     * Test that int '2' is not less than int '1'.
     *
     * @covers \ExtendsFramework\Validator\Comparison\LessThanValidator::__construct()
     * @covers \ExtendsFramework\Validator\Comparison\LessThanValidator::validate()
     * @covers \ExtendsFramework\Validator\Comparison\LessThanValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new LessThanValidator(1);
        $result = $validator->validate(2);

        $this->assertFalse($result->isValid());
    }

    /**
     * Factory.
     *
     * Test that factory returns a LessOrEqualValidator.
     *
     * @covers \ExtendsFramework\Validator\Comparison\LessThanValidator::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $validator = LessThanValidator::factory(ValidatorInterface::class, $serviceLocator, [
            'subject' => 5.5,
        ]);

        $this->assertInstanceOf(ValidatorInterface::class, $validator);
    }
}
