<?php
declare(strict_types=1);

namespace ExtendsFramework\Validator\Type;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ExtendsFramework\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class NumberValidatorTest extends TestCase
{
    /**
     * Valid number values.
     *
     * @return array
     */
    public function validNumberValues(): array
    {
        return [
            [8],
            [10.1],
            [-10],
            [-10.1],
        ];
    }

    /**
     * Valid.
     *
     * Test that value is a number.
     *
     * @param mixed $number
     * @covers       \ExtendsFramework\Validator\Type\NumberValidator::validate()
     * @dataProvider validNumberValues
     */
    public function testValid($number): void
    {
        $validator = new NumberValidator();
        $result = $validator->validate($number);

        $this->assertTrue($result->isValid());
    }

    /**
     * Invalid number values.
     *
     * @return array
     */
    public function invalidNumberValues(): array
    {
        return [
            ['8'],
            ['foo'],
            ['-10'],
            ['-10.1'],
            [[]],
        ];
    }

    /**
     * Invalid.
     *
     * Test that string value 'foo' is an valid integer.
     *
     * @param mixed $number
     * @covers       \ExtendsFramework\Validator\Type\NumberValidator::validate()
     * @covers       \ExtendsFramework\Validator\Type\NumberValidator::getTemplates()
     * @dataProvider invalidNumberValues
     */
    public function testInvalid($number): void
    {
        $validator = new NumberValidator();
        $result = $validator->validate($number);

        $this->assertFalse($result->isValid());
    }

    /**
     * Factory.
     *
     * Test that factory returns a NumberValidator.
     *
     * @covers \ExtendsFramework\Validator\Type\NumberValidator::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $validator = NumberValidator::factory(NumberValidator::class, $serviceLocator);

        $this->assertInstanceOf(ValidatorInterface::class, $validator);
    }
}
