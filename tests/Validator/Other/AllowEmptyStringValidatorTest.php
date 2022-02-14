<?php
declare(strict_types=1);

namespace ExtendsFramework\Validator\Other;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ExtendsFramework\Validator\Result\ResultInterface;
use ExtendsFramework\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class AllowEmptyStringValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that value will be passed to inner validator and result will be returned.
     *
     * @covers \ExtendsFramework\Validator\Other\AllowEmptyStringValidator::__construct()
     * @covers \ExtendsFramework\Validator\Other\AllowEmptyStringValidator::validate()
     */
    public function testValid(): void
    {
        $innerResult = $this->createMock(ResultInterface::class);

        $innerValidator = $this->createMock(ValidatorInterface::class);
        $innerValidator
            ->expects($this->once())
            ->method('validate')
            ->with('foo')
            ->willReturn($innerResult);

        /**
         * @var ValidatorInterface $innerValidator
         */
        $validator = new AllowEmptyStringValidator($innerValidator);

        $result = $validator->validate('foo');
        $this->assertSame($innerResult, $result);
    }

    /**
     * Valid empty string value.
     *
     * Test that '' is a valid value.
     *
     * @covers \ExtendsFramework\Validator\Other\AllowEmptyStringValidator::__construct()
     * @covers \ExtendsFramework\Validator\Other\AllowEmptyStringValidator::validate()
     */
    public function testValidNullValue(): void
    {
        $inner = $this->createMock(ValidatorInterface::class);
        $inner
            ->expects($this->never())
            ->method('validate');

        /**
         * @var ValidatorInterface $inner
         */
        $validator = new AllowEmptyStringValidator($inner);

        $result = $validator->validate('');
        $this->assertTrue($result->isValid());
    }

    /**
     * Factory.
     *
     * Test that factory returns an instanceof of ValidatorInterface.
     *
     * @covers \ExtendsFramework\Validator\Other\AllowEmptyStringValidator::factory()
     * @covers \ExtendsFramework\Validator\Other\AllowEmptyStringValidator::__construct()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->once())
            ->method('getService')
            ->with(ValidatorInterface::class, ['foo' => 'bar'])
            ->willReturn($this->createMock(ValidatorInterface::class));

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $validator = AllowEmptyStringValidator::factory(AllowEmptyStringValidator::class, $serviceLocator, [
            'name' => ValidatorInterface::class,
            'options' => [
                'foo' => 'bar',
            ],
        ]);

        $this->assertInstanceOf(ValidatorInterface::class, $validator);
    }
}
