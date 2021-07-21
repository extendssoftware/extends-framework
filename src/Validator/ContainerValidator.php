<?php
declare(strict_types=1);

namespace ExtendsFramework\Validator;

use ExtendsFramework\Validator\Result\Container\ContainerResult;
use ExtendsFramework\Validator\Result\ResultInterface;

class ContainerValidator implements ValidatorInterface
{
    /**
     * Indexed array with validator validators to use for validation.
     *
     * @var InterruptValidator[]
     */
    private array $validators = [];

    /**
     * @inheritdoc
     */
    public function validate($value, $context = null): ResultInterface
    {
        $container = new ContainerResult();
        foreach ($this->validators as $validator) {
            $result = $validator->validate($value, $context);
            $container->addResult($result);

            if (!$result->isValid() && $validator->mustInterrupt()) {
                break;
            }
        }

        return $container;
    }

    /**
     * Add validator to container.
     *
     * When interrupt is true, validation will stop if validator result is invalid. Default value is false.
     *
     * @param ValidatorInterface $validator
     * @param bool|null          $interrupt
     *
     * @return ContainerValidator
     */
    public function addValidator(ValidatorInterface $validator, bool $interrupt = null): ContainerValidator
    {
        $this->validators[] = new InterruptValidator($validator, $interrupt);

        return $this;
    }
}
