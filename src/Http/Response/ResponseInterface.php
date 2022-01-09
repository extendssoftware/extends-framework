<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Response;

interface ResponseInterface
{
    /**
     * Add header with name for value.
     *
     * If header with name already exists, it will be added to the array.
     *
     * @param string $name
     * @param string $value
     *
     * @return ResponseInterface
     */
    public function andHeader(string $name, string $value): ResponseInterface;

    /**
     * Return body.
     *
     * @return mixed
     */
    public function getBody();

    /**
     * Return headers.
     *
     * @return mixed[]
     */
    public function getHeaders(): array;

    /**
     * Get header value for name.
     *
     * Default value default will be returned when header with name does not exists.
     *
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getHeader(string $name, $default = null);

    /**
     * Return status code.
     *
     * @return int
     */
    public function getStatusCode(): int;

    /**
     * Return new instance with body.
     *
     * @param mixed $body
     *
     * @return ResponseInterface
     */
    public function withBody($body): ResponseInterface;

    /**
     * Set header with name for value.
     *
     * If header with name already exists, it will be overwritten.
     *
     * @param string $name
     * @param string $value
     *
     * @return ResponseInterface
     */
    public function withHeader(string $name, string $value): ResponseInterface;

    /**
     * Return new instance with headers.
     *
     * @param mixed[] $headers
     *
     * @return ResponseInterface
     */
    public function withHeaders(array $headers): ResponseInterface;

    /**
     * Return new instance with statusCode.
     *
     * @param int $statusCode
     *
     * @return ResponseInterface
     */
    public function withStatusCode(int $statusCode): ResponseInterface;
}
