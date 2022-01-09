<?php
declare(strict_types=1);

namespace ExtendsFramework\Validator\Logical;

use ExtendsFramework\ServiceLocator\ServiceLocatorException;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ExtendsFramework\Validator\Exception\TemplateNotFound;
use ExtendsFramework\Validator\Result\ResultInterface;

class NotValidator extends AbstractLogicalValidator
{
    /**
     * When value is not false.
     *
     * @const string
     */
    public const NOT_FALSE = 'notFalse';

    /**
     * @inheritDoc
     * @throws ServiceLocatorException
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        $validators = [];
        foreach ($extra['validators'] ?? [] as $validator) {
            $validators[] = $serviceLocator->getService($validator['name'], $validator['options'] ?? []);
        }

        return new NotValidator($validators);
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, $context = null): ResultInterface
    {
        if (!(bool)$value) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::NOT_FALSE);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_FALSE => 'Value is not equal to false.',
        ];
    }
}
