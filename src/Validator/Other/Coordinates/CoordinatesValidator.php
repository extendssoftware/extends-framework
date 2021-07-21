<?php
declare(strict_types=1);

namespace ExtendsFramework\Validator\Other\Coordinates;

use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ExtendsFramework\Validator\AbstractValidator;
use ExtendsFramework\Validator\Exception\TemplateNotFound;
use ExtendsFramework\Validator\Object\PropertiesValidator;
use ExtendsFramework\Validator\Other\Coordinates\Coordinate\LatitudeValidator;
use ExtendsFramework\Validator\Other\Coordinates\Coordinate\LongitudeValidator;
use ExtendsFramework\Validator\Result\ResultInterface;

class CoordinatesValidator extends AbstractValidator
{
    /**
     * Latitude object key.
     *
     * @var string
     */
    private string $latitude;

    /**
     * Longitude object key.
     *
     * @var string
     */
    private string $longitude;

    /**
     * CoordinatesValidator constructor.
     *
     * @param string|null $latitude
     * @param string|null $longitude
     */
    public function __construct(string $latitude = null, string $longitude = null)
    {
        $this->latitude = $latitude ?: 'latitude';
        $this->longitude = $longitude ?: 'longitude';
    }

    /**
     * @inheritDoc
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        return new static(
            $extra['latitude'] ?? null,
            $extra['longitude'] ?? null
        );
    }

    /**
     * @inheritDoc
     * @throws TemplateNotFound
     */
    public function validate($value, $context = null): ResultInterface
    {
        $validator = new PropertiesValidator([
            $this->latitude => new LatitudeValidator(),
            $this->longitude => new LongitudeValidator(),
        ]);

        return $validator->validate($value);
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
