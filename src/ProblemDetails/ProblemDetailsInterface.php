<?php
declare(strict_types=1);

namespace ExtendsFramework\ProblemDetails;

interface ProblemDetailsInterface
{
    /**
     * Get type.
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle(): string;

    /**
     * Get detail.
     *
     * @return string
     */
    public function getDetail(): string;

    /**
     * Get HTTP status code.
     *
     * @return int
     */
    public function getStatus(): int;

    /**
     * Get instance.
     *
     * @return string|null
     */
    public function getInstance(): ?string;

    /**
     * Return additional members.
     *
     * @return array|null
     */
    public function getAdditional(): ?array;
}
