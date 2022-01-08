<?php
declare(strict_types=1);

namespace ExtendsFramework\Validator\Type;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ExtendsFramework\Validator\AbstractValidator;
use ExtendsFramework\Validator\Exception\TemplateNotFound;
use ExtendsFramework\Validator\Result\ResultInterface;

class BooleanValidator extends AbstractValidator
{
    /**
     * When value is not a boolean.
     *
     * @const string
     */
    public const NOT_BOOLEAN = 'notBoolean';

    /**
     * @inheritDoc
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        return new BooleanValidator();
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, $context = null): ResultInterface
    {
        if (is_bool($value)) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::NOT_BOOLEAN, [
            'type' => gettype($value),
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_BOOLEAN => 'Value must be a boolean, got {{type}}.',
        ];
    }
}
