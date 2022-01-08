<?php
declare(strict_types=1);

namespace ExtendsFramework\Validator\Comparison;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ExtendsFramework\Validator\AbstractValidator;
use ExtendsFramework\Validator\Exception\TemplateNotFound;
use ExtendsFramework\Validator\Result\ResultInterface;

class NotIdenticalValidator extends AbstractValidator
{
    /**
     * When value is not identical to context.
     *
     * @const string
     */
    public const IS_IDENTICAL = 'isIdentical';

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
        return new NotIdenticalValidator(
            $extra['subject']
        );
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, $context = null): ResultInterface
    {
        if ($value !== $this->subject) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::IS_IDENTICAL, [
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
            self::IS_IDENTICAL => 'Value {{value}} is identical to subject {{subject}}.',
        ];
    }
}
