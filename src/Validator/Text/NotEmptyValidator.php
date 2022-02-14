<?php
declare(strict_types=1);

namespace ExtendsFramework\Validator\Text;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ExtendsFramework\Validator\AbstractValidator;
use ExtendsFramework\Validator\Exception\TemplateNotFound;
use ExtendsFramework\Validator\Result\ResultInterface;
use ExtendsFramework\Validator\Type\StringValidator;

class NotEmptyValidator extends AbstractValidator
{
    /**
     * When text is an empty string.
     *
     * @var string
     */
    public const EMPTY = 'empty';

    /**
     * @inheritDoc
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        return new NotEmptyValidator();
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, $context = null): ResultInterface
    {
        $result = (new StringValidator())->validate($value);
        if (!$result->isValid()) {
            return $result;
        }

        if (!empty($value)) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::EMPTY);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::EMPTY => 'Text can not be left empty.',
        ];
    }
}
