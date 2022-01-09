<?php
declare(strict_types=1);

namespace ExtendsFramework\Validator\Type;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ExtendsFramework\Validator\AbstractValidator;
use ExtendsFramework\Validator\Exception\TemplateNotFound;
use ExtendsFramework\Validator\Result\ResultInterface;

class IterableValidator extends AbstractValidator
{
    /**
     * When value is not iterable.
     *
     * @const string
     */
    public const NOT_ITERABLE = 'notIterable';

    /**
     * @inheritDoc
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        return new IterableValidator();
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, $context = null): ResultInterface
    {
        if (is_iterable($value)) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::NOT_ITERABLE, [
            'type' => gettype($value),
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_ITERABLE => 'Value must be iterable, got {{type}}.',
        ];
    }
}
