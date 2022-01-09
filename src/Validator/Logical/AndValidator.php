<?php
declare(strict_types=1);

namespace ExtendsFramework\Validator\Logical;

use ExtendsFramework\ServiceLocator\ServiceLocatorException;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ExtendsFramework\Validator\Result\ResultInterface;

class AndValidator extends AbstractLogicalValidator
{
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

        return new AndValidator($validators);
    }

    /**
     * @inheritDoc
     */
    public function validate($value, $context = null): ResultInterface
    {
        foreach ($this->getValidators() as $validator) {
            $result = $validator->validate($value, $context);
            if (!$result->isValid()) {
                return $result;
            }
        }

        return $this->getValidResult();
    }

    /**
     * @inheritDoc
     * @codeCoverageIgnore
     */
    protected function getTemplates(): array
    {
        return [];
    }
}
