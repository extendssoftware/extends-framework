<?php
declare(strict_types=1);

namespace ExtendsFramework\Validator\Type;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ExtendsFramework\Validator\AbstractValidator;
use ExtendsFramework\Validator\Exception\TemplateNotFound;
use ExtendsFramework\Validator\Result\ResultInterface;

class StringValidator extends AbstractValidator
{
    /**
     * When value is not a string.
     *
     * @const string
     */
    public const NOT_STRING = 'notString';

    /**
     * @inheritDoc
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        return new StringValidator();
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, $context = null): ResultInterface
    {
        if (is_string($value)) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::NOT_STRING, [
            'type' => gettype($value),
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_STRING => 'Value must be a string, got {{type}}.',
        ];
    }
}
