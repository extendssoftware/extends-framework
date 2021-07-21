<?php
declare(strict_types=1);

namespace ExtendsFramework\Validator\Other\Coordinates\Coordinate;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ExtendsFramework\Validator\AbstractValidator;
use ExtendsFramework\Validator\Exception\TemplateNotFound;
use ExtendsFramework\Validator\Result\ResultInterface;
use ExtendsFramework\Validator\Type\NumberValidator;

class LongitudeValidator extends AbstractValidator
{
    /**
     * When longitude is lower than min or greater than max.
     *
     * @var string
     */
    public const LONGITUDE_OUT_OF_BOUND = 'longitudeOutOfBound';

    /**
     * Minimal longitude value.
     *
     * @var int
     */
    private int $min = -90;

    /**
     * Maximum longitude value.
     *
     * @var int
     */
    private int $max = 90;

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
        $result = (new NumberValidator())->validate($value);
        if (!$result->isValid()) {
            return $result;
        }

        if ($value < $this->min || $value > $this->max) {
            return $this->getInvalidResult(self::LONGITUDE_OUT_OF_BOUND, [
                'min' => $this->min,
                'max' => $this->max,
                'longitude' => $value,
            ]);
        }

        return $this->getValidResult();
    }

    /**
     * @inheritDoc
     */
    protected function getTemplates(): array
    {
        return [
            self::LONGITUDE_OUT_OF_BOUND =>
                'Longitude is out of bound and must be between {{min}} and {{max}} inclusive, got {{longitude}}.',
        ];
    }
}
