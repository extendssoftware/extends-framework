<?php
declare(strict_types=1);

namespace ExtendsFramework\Validator\Text;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ExtendsFramework\Validator\AbstractValidator;
use ExtendsFramework\Validator\Exception\TemplateNotFound;
use ExtendsFramework\Validator\Result\ResultInterface;
use ExtendsFramework\Validator\Type\StringValidator;

class UuidValidator extends AbstractValidator
{
    /**
     * When value is not an UUID.
     *
     * @const string
     */
    public const NOT_UUID = 'notUuid';

    /**
     * UUID regular expression.
     *
     * @var string
     */
    private string $pattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i';

    /**
     * @inheritDoc
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        return new static();
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

        return $this->getInvalidResult(self::NOT_UUID, [
            'value' => $value,
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::NOT_UUID => 'Value {{value}} must be a valid UUID.',
        ];
    }
}
