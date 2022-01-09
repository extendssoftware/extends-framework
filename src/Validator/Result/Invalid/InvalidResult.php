<?php
declare(strict_types=1);

namespace ExtendsFramework\Validator\Result\Invalid;

use ExtendsFramework\Validator\Result\ResultInterface;

class InvalidResult implements ResultInterface
{
    /**
     * Error code.
     *
     * @var string
     */
    private string $code;

    /**
     * Error message.
     *
     * @var string
     */
    private string $message;

    /**
     * Message parameters.
     *
     * @var mixed[]
     */
    private array $parameters;

    /**
     * Violation constructor.
     *
     * @param string  $code
     * @param string  $message
     * @param mixed[] $parameters
     */
    public function __construct(string $code, string $message, array $parameters)
    {
        $this->code = $code;
        $this->message = $message;
        $this->parameters = $parameters;
    }

    /**
     * @inheritDoc
     */
    public function isValid(): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     * @return mixed[]
     */
    public function jsonSerialize(): array
    {
        return [
            'code' => $this->code,
            'message' => $this->message,
            'parameters' => $this->parameters,
        ];
    }

    /**
     * Return result as a string.
     *
     * @return string
     */
    public function __toString(): string
    {
        $replacement = [];
        foreach ($this->parameters as $key => $parameter) {
            $replacement[sprintf('{{%s}}', $key)] = $parameter;
        }

        return strtr($this->message, $replacement);
    }
}
