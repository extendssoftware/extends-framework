<?php
declare(strict_types=1);

namespace ExtendsFramework\Validator\Comparison;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ExtendsFramework\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class LessOrEqualValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that int '1' is less than int '2' and int '1' is equal to int '1'.
     *
     * @covers \ExtendsFramework\Validator\Comparison\LessOrEqualValidator::__construct()
     * @covers \ExtendsFramework\Validator\Comparison\LessOrEqualValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new LessOrEqualValidator(2);
        $result1 = $validator->validate(1);
        $result2 = $validator->validate(2);

        $this->assertTrue($result1->isValid());
        $this->assertTrue($result2->isValid());
    }

    /**
     * Invalid.
     *
     * Test that int '1' is not greater than or equal to int '2'.
     *
     * @covers \ExtendsFramework\Validator\Comparison\LessOrEqualValidator::__construct()
     * @covers \ExtendsFramework\Validator\Comparison\LessOrEqualValidator::validate()
     * @covers \ExtendsFramework\Validator\Comparison\LessOrEqualValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new LessOrEqualValidator(1);
        $result = $validator->validate(2);

        $this->assertFalse($result->isValid());
    }

    /**
     * Factory.
     *
     * Test that factory returns a LessOrEqualValidator.
     *
     * @covers \ExtendsFramework\Validator\Comparison\LessOrEqualValidator::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $validator = LessOrEqualValidator::factory(ValidatorInterface::class, $serviceLocator, [
            'subject' => 5.5,
        ]);

        $this->assertInstanceOf(ValidatorInterface::class, $validator);
    }
}
