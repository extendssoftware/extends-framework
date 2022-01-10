<?php
declare(strict_types=1);

namespace ExtendsFramework\Merger\Recursive;

use ExtendsFramework\Merger\MergerInterface;

class RecursiveMerger implements MergerInterface
{
    /**
     * When a key in the right array does not exist in the left array, it will be added to the left array. The right
     * value will always overwrite the left value when one of both is not an array.
     *
     * When the values in the right array and left array are both arrays, they will be merged. If the value in the
     * right array is associative, they will be merged recursively with this function. When the value in the right
     * array is indexed, it will be merged (compare items normally) with the array from the left array and values
     * will be unique.
     *
     * @inheritDoc
     */
    public function merge(array $left, array $right): array
    {
        foreach ($right as $key => $value) {
            if (is_array($value) && isset($left[$key]) && is_array($left[$key])) {
                if (array_values($value) !== $value) {
                    $left[$key] = $this->merge($left[$key], $value);
                } else {
                    $left[$key] = array_merge($left[$key], $value);
                    $left[$key] = array_unique($left[$key], SORT_REGULAR);
                }
            } else {
                $left[$key] = $value;
            }
        }

        return $left;
    }
}
