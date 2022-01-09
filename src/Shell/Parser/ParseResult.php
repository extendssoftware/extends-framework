<?php
declare(strict_types=1);

namespace ExtendsFramework\Shell\Parser;

class ParseResult implements ParseResultInterface
{
    /**
     * Parsed data.
     *
     * @var mixed[]
     */
    private array $parsed;

    /**
     * Remaining data when not in strict mode.
     *
     * @var mixed[]
     */
    private array $remaining;

    /**
     * If parsing was done in strict mode.
     *
     * @var bool
     */
    private bool $strict;

    /**
     * Create new parse result.
     *
     * @param mixed[] $parsed
     * @param mixed[] $remaining
     * @param bool  $strict
     */
    public function __construct(array $parsed, array $remaining, bool $strict)
    {
        $this->parsed = $parsed;
        $this->remaining = $remaining;
        $this->strict = $strict;
    }

    /**
     * @inheritDoc
     */
    public function getParsed(): array
    {
        return $this->parsed;
    }

    /**
     * @inheritDoc
     */
    public function getRemaining(): array
    {
        return $this->remaining;
    }

    /**
     * @inheritDoc
     */
    public function isStrict(): bool
    {
        return $this->strict;
    }
}
