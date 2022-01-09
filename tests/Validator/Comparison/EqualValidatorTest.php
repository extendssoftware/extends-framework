<?php
declare(strict_types=1);

namespace ExtendsFramework\Validator\Comparison;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ExtendsFramework\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class EqualValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that string '1' is equal to int '1'.
     *
     * @covers \ExtendsFramework\Validator\Comparison\EqualValidator::__construct()
     * @covers \ExtendsFramework\Validator\Comparison\EqualValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new EqualValidator(1);
        $result = $validator->validate('1');

        $this->assertTrue($result->isValid());
    }

    /**
     * Invalid.
     *
     * Test that string '1' is not equal to string '2'.
     *
     * @covers \ExtendsFramework\Validator\Comparison\EqualValidator::__construct()
     * @covers \ExtendsFramework\Validator\Comparison\EqualValidator::validate()
     * @covers \ExtendsFramework\Validator\Comparison\EqualValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new EqualValidator(2);
        $result = $validator->validate('1');

        $this->assertFalse($result->isValid());
    }

    /**
     * Factory.
     *
     * Test that factory returns a EqualValidator.
     *
     * @covers \ExtendsFramework\Validator\Comparison\EqualValidator::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $validator = EqualValidator::factory(ValidatorInterface::class, $serviceLocator, [
            'subject' => 5.5,
        ]);

        $this->assertInstanceOf(ValidatorInterface::class, $validator);
    }
}
