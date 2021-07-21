<?php
declare(strict_types=1);

namespace ExtendsFramework\ProblemDetails\Serializer\Json;

use ExtendsFramework\ProblemDetails\ProblemDetailsInterface;
use ExtendsFramework\ProblemDetails\Serializer\SerializerInterface;

class JsonSerializer implements SerializerInterface
{
    /**
     * @inheritDoc
     */
    public function serialize(ProblemDetailsInterface $problem): string
    {
        return json_encode(
            array_filter(
                array_merge(
                    [
                        'type' => $problem->getType(),
                        'title' => $problem->getTitle(),
                        'detail' => $problem->getDetail(),
                        'status' => $problem->getStatus(),
                        'instance' => $problem->getInstance(),
                    ],
                    $problem->getAdditional() ?: []
                )
            ),
            JSON_UNESCAPED_SLASHES
        );
    }
}
