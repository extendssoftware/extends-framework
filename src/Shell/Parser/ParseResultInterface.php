<?php
declare(strict_types=1);

namespace ExtendsFramework\Shell\Parser;

interface ParseResultInterface
{
    /**
     * Get parsed data.
     *
     * @return mixed[]
     */
    public function getParsed(): array;

    /**
     * Get remaining data when not in strict mode.
     *
     * @return mixed[]
     */
    public function getRemaining(): array;

    /**
     * If parsing was done in strict mode.
     *
     * @return bool
     */
    public function isStrict(): bool;
}
