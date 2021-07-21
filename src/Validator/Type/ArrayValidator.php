<?php
declare(strict_types=1);

namespace ExtendsFramework\Validator\Type;

use ExtendsFramework\Validator\Exception\TemplateNotFound;
use ExtendsFramework\Validator\Result\ResultInterface;

class ArrayValidator extends AbstractTypeValidator
{
    /**
     * When value is not an array.
     *
     * @const string
     */
    public const NOT_ARRAY = 'notArray';

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, $context = null): ResultInterface
    {
        if (is_array($value)) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::NOT_ARRAY, [
            'type' => gettype($value),
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_ARRAY => 'Value must be an array, got {{type}}.',
        ];
    }
}
