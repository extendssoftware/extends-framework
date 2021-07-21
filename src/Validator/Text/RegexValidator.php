<?php
declare(strict_types=1);

namespace ExtendsFramework\Validator\Text;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ExtendsFramework\Validator\AbstractValidator;
use ExtendsFramework\Validator\Exception\TemplateNotFound;
use ExtendsFramework\Validator\Result\ResultInterface;
use ExtendsFramework\Validator\Type\StringValidator;

class RegexValidator extends AbstractValidator
{
    /**
     * When value does not match pattern.
     *
     * @const string
     */
    public const NOT_VALID = 'notValid';

    /**
     * Regular expression to validate.
     *
     * @var string
     */
    private string $pattern;

    /**
     * Create new regular expression Validator for $pattern.
     *
     * @param string $pattern
     */
    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * @inheritDoc
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        return new static(
            $extra['pattern']
        );
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

        if (preg_match($this->pattern, $value) === 1) {
            return $this->getValidResult();
        }

        return $this->getInvalidResult(self::NOT_VALID, [
            'value' => $value,
            'pattern' => $this->pattern,
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_VALID => 'Value {{value}} must match regular expression {{pattern}}.',
        ];
    }
}
