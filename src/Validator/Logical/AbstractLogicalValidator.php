<?php
declare(strict_types=1);

namespace ExtendsFramework\Validator\Logical;

use ExtendsFramework\ServiceLocator\ServiceLocatorException;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use ExtendsFramework\Validator\AbstractValidator;
use ExtendsFramework\Validator\ValidatorInterface;

abstract class AbstractLogicalValidator extends AbstractValidator
{
    /**
     * Validators to validate.
     *
     * @var ValidatorInterface[]
     */
    private array $validators = [];

    /**
     * AbstractLogicalValidator constructor.
     *
     * @param mixed[]|null $validators
     */
    public function __construct(array $validators = null)
    {
        foreach ($validators ?? [] as $validator) {
            $this->addValidator($validator);
        }
    }

    /**
     * Add validator.
     *
     * @param ValidatorInterface $validator
     *
     * @return AbstractLogicalValidator
     */
    public function addValidator(ValidatorInterface $validator): AbstractLogicalValidator
    {
        $this->validators[] = $validator;

        return $this;
    }

    /**
     * Get validators.
     *
     * @return ValidatorInterface[]
     */
    protected function getValidators(): array
    {
        return $this->validators;
    }
}
