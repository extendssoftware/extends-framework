<?php
declare(strict_types=1);

namespace ExtendsFramework\Merger;

interface MergerInterface
{
    /**
     * Merge right into left.
     *
     * @param mixed[] $left
     * @param mixed[] $right
     *
     * @return mixed[]
     * @throws MergerException
     */
    public function merge(array $left, array $right): array;
}
