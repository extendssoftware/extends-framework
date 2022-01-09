<?php
declare(strict_types=1);

namespace ExtendsFramework\Validator\Type;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ExtendsFramework\Validator\AbstractValidator;
use ExtendsFramework\Validator\Exception\TemplateNotFound;
use ExtendsFramework\Validator\Result\ResultInterface;

class NumberValidator extends AbstractValidator
{
    /**
     * When value is not a number.
     *
     * @const string
     */
    public const NOT_NUMBER = 'notNumber';

    /**
     * @inheritDoc
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        return new NumberValidator();
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, $context = null): ResultInterface
    {
        if (is_int($value) || is_float($value)) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::NOT_NUMBER, [
            'type' => gettype($value),
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_NUMBER => 'Value must be a number, got {{type}}.',
        ];
    }
}
