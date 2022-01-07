<?php
declare(strict_types=1);

namespace ExtendsFramework\Validator\Type;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ExtendsFramework\Validator\Result\ResultInterface;
use ExtendsFramework\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class AbstractTypeValidatorTest extends TestCase
{
    /**
     * Dummy abstract type validator.
     *
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->validator = new class extends AbstractTypeValidator
        {
            /**
             * @inheritDoc
             */
            public function validate($value, $context = null): ResultInterface
            {
                return $this->getValidResult();
            }

            /**
             * @inheritDoc
             */
            protected function getTemplates(): array
            {
                return [];
            }
        };
    }

    /**
     * Factory.
     *
     * Test that factory returns a AbstractTypeValidator.
     *
     * @covers \ExtendsFramework\Validator\Type\AbstractTypeValidator::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $validator = $this->validator::factory(AbstractTypeValidator::class, $serviceLocator);

        $this->assertInstanceOf(ValidatorInterface::class, $validator);
    }
}
