<?php
declare(strict_types=1);

namespace ExtendsFramework\Validator;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ExtendsFramework\Validator\Exception\TemplateNotFound;
use ExtendsFramework\Validator\Result\ResultInterface;
use PHPUnit\Framework\TestCase;

class AbstractValidatorTest extends TestCase
{
    /**
     * Dummy abstract validator.
     *
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->validator = new class extends AbstractValidator {
            /**
             * @inheritDoc
             */
            protected function getTemplates(): array
            {
                return [
                    'bar' => 'Fancy error message.',
                ];
            }

            /**
             * @inheritDoc
             */
            public static function factory(
                string $key,
                ServiceLocatorInterface $serviceLocator,
                array $extra = null
            ): object {
                return new static();
            }

            /**
             * @inheritDoc
             */
            public function validate($value, $context = null): ResultInterface
            {
                if ($context === true) {
                    return $this->getValidResult();
                }
                if ($context === false) {
                    return $this->getInvalidResult('bar', []);
                }

                return $this->getInvalidResult('foo', []);
            }
        };
    }

    /**
     * Valid result.
     *
     * Test that a valid result will be returned.
     *
     * @covers \ExtendsFramework\Validator\AbstractValidator::validate()
     * @covers \ExtendsFramework\Validator\AbstractValidator::getValidResult()
     */
    public function testValidResult(): void
    {
        $result = $this->validator->validate('foo', true);

        $this->assertTrue($result->isValid());
    }

    /**
     * Invalid result.
     *
     * Test that a invalid result will be returned.
     *
     * @covers \ExtendsFramework\Validator\AbstractValidator::validate()
     * @covers \ExtendsFramework\Validator\AbstractValidator::getInvalidResult()
     */
    public function testInvalidResult(): void
    {
        $result = $this->validator->validate('foo', false);

        $this->assertFalse($result->isValid());
    }

    /**
     * Template not found.
     *
     * Test that exception TemplateNotFound will be thrown when template for key 'foo' can not be found.
     *
     * @covers \ExtendsFramework\Validator\AbstractValidator::validate()
     * @covers \ExtendsFramework\Validator\AbstractValidator::getInvalidResult()
     * @covers \ExtendsFramework\Validator\Exception\TemplateNotFound::__construct()
     */
    public function testTemplateNotFound(): void
    {
        $this->expectException(TemplateNotFound::class);
        $this->expectExceptionMessage('No invalid result template found for key "foo".');

        $this->validator->validate('foo');
    }
}
