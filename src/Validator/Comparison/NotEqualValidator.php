<?php
declare(strict_types=1);

namespace ExtendsFramework\Validator\Comparison;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ExtendsFramework\Validator\AbstractValidator;
use ExtendsFramework\Validator\Exception\TemplateNotFound;
use ExtendsFramework\Validator\Result\ResultInterface;

class NotEqualValidator extends AbstractValidator
{
    /**
     * When value is not equal to context.
     *
     * @const string
     */
    public const IS_EQUAL = 'isEqual';

    /**
     * Value to compare to.
     *
     * @var mixed
     */
    private $subject;

    /**
     * AbstractComparisonValidator constructor.
     *
     * @param mixed $subject
     */
    public function __construct($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @inheritDoc
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        return new NotEqualValidator(
            $extra['subject']
        );
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, $context = null): ResultInterface
    {
        /** @noinspection TypeUnsafeComparisonInspection */
        if ($value != $this->subject) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::IS_EQUAL, [
            'value' => $value,
            'subject' => $this->subject,
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::IS_EQUAL => 'Value {{value}} is equal to subject {{subject}}.',
        ];
    }
}
