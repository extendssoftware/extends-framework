<?php
declare(strict_types=1);

namespace ExtendsFramework\ProblemDetails\Serializer;

use ExtendsFramework\ProblemDetails\ProblemDetailsInterface;

interface SerializerInterface
{
    /**
     * Serialize problem.
     *
     * @param ProblemDetailsInterface $problem
     *
     * @return string
     */
    public function serialize(ProblemDetailsInterface $problem): string;
}
