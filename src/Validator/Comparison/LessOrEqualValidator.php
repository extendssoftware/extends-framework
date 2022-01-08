<?php
declare(strict_types=1);

namespace ExtendsFramework\Validator\Comparison;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ExtendsFramework\Validator\AbstractValidator;
use ExtendsFramework\Validator\Exception\TemplateNotFound;
use ExtendsFramework\Validator\Result\ResultInterface;

class LessOrEqualValidator extends AbstractValidator
{
    /**
     * When value is not less than or equal to context.
     *
     * @const string
     */
    public const NOT_LESS_OR_EQUAL = 'notLessOrEqual';

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
        return new LessOrEqualValidator(
            $extra['subject']
        );
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, $context = null): ResultInterface
    {
        if ($value <= $this->subject) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::NOT_LESS_OR_EQUAL, [
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
            self::NOT_LESS_OR_EQUAL => 'Value {{value}} is not less than or equal to subject {{subject}}.',
        ];
    }
}
