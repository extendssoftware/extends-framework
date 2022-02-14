<?php
declare(strict_types=1);

namespace ExtendsFramework\Validator\Other;

use ExtendsFramework\ServiceLocator\ServiceLocatorException;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ExtendsFramework\Validator\AbstractValidator;
use ExtendsFramework\Validator\Exception\TemplateNotFound;
use ExtendsFramework\Validator\Result\ResultInterface;
use ExtendsFramework\Validator\Text\LengthValidator;
use ExtendsFramework\Validator\ValidatorInterface;

class AllowEmptyStringValidator extends AbstractValidator
{
    /**
     * Inner validator.
     *
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    /**
     * AllowEmptyStringValidator constructor.
     *
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @inheritDoc
     * @throws ServiceLocatorException
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        /** @var ValidatorInterface $validator */
        /** @phpstan-ignore-next-line */
        $validator = $serviceLocator->getService($extra['name'], $extra['options'] ?? []);

        return new AllowEmptyStringValidator($validator);
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, $context = null): ResultInterface
    {
        $result = (new LengthValidator(0, 0))->validate($value, $context);
        if ($result->isValid()) {
            return $result;
        }

        return $this->validator->validate($value, $context);
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
