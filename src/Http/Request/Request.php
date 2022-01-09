<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Request;

use ExtendsFramework\Http\Request\Exception\InvalidRequestBody;
use ExtendsFramework\Http\Request\Uri\Uri;
use ExtendsFramework\Http\Request\Uri\UriInterface;
use ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class Request implements RequestInterface, StaticFactoryInterface
{
    /**
     * Custom request attributes.
     *
     * @var mixed[]
     */
    private array $attributes = [];

    /**
     * Post data.
     *
     * @var mixed
     */
    private $body;

    /**
     * Request headers.
     *
     * @var mixed[]
     */
    private array $headers = [];

    /**
     * Request method.
     *
     * @var string
     */
    private string $method;

    /**
     * Request URI.
     *
     * @var UriInterface|null
     */
    private ?UriInterface $uri = null;

    /**
     * @inheritDoc
     */
    public function andAttribute(string $name, $value): RequestInterface
    {
        $clone = clone $this;
        $clone->attributes[$name] = $value;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function andHeader(string $name, $value, bool $replace = null): RequestInterface
    {
        $clone = clone $this;
        if (array_key_exists($name, $clone->headers)) {
            if (!is_array($clone->headers[$name])) {
                $clone->headers[$name] = [
                    $clone->headers[$name],
                ];
            }

            $clone->headers[$name][] = $value;
        } else {
            $clone->headers[$name] = $value;
        }

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @inheritDoc
     */
    public function getAttribute(string $key, $default = null)
    {
        return $this->attributes[$key] ?? $default;
    }

    /**
     * @inheritDoc
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @inheritDoc
     */
    public function getHeader(string $name, $default = null)
    {
        return $this->headers[$name] ?? $default;
    }

    /**
     * @inheritDoc
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @inheritDoc
     */
    public function getUri(): UriInterface
    {
        if ($this->uri === null) {
            $this->uri = new Uri();
        }

        return $this->uri;
    }

    /**
     * @inheritDoc
     */
    public function withAttributes(array $attributes): RequestInterface
    {
        $clone = clone $this;
        $clone->attributes = $attributes;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withBody($body): RequestInterface
    {
        $clone = clone $this;
        $clone->body = $body;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withHeader(string $name, string $value): RequestInterface
    {
        $clone = clone $this;
        $clone->headers[$name] = $value;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withHeaders(array $headers): RequestInterface
    {
        $clone = clone $this;
        $clone->headers = $headers;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withMethod(string $method): RequestInterface
    {
        $clone = clone $this;
        $clone->method = $method;

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withUri(UriInterface $uri): RequestInterface
    {
        $clone = clone $this;
        $clone->uri = $uri;

        return $clone;
    }

    /**
     * @inheritDoc
     * @throws InvalidRequestBody
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        return static::fromEnvironment(
            $extra['environment'] ?? $_SERVER,
            $extra['stream'] ?? fopen('php://input', 'r')
        );
    }

    /**
     * Construct from environment variables.
     *
     * @param mixed[]  $environment
     * @param resource $stream
     *
     * @return RequestInterface
     * @throws InvalidRequestBody
     */
    public static function fromEnvironment(array $environment, $stream): RequestInterface
    {
        $headers = [];
        foreach ($environment as $name => $value) {
            if (strpos($name, 'HTTP_') === 0) {
                $name = substr($name, 5);
                $name = str_replace('_', ' ', $name);
                $name = strtolower($name);
                $name = ucwords($name);
                $name = str_replace(' ', '-', $name);

                $headers[$name] = $value;
            }
        }

        $input = stream_get_contents($stream);
        if (!empty($input)) {
            $body = json_decode($input, false);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new InvalidRequestBody(json_last_error_msg());
            }
        }

        return (new Request())
            ->withMethod($environment['REQUEST_METHOD'])
            ->withBody($body ?? null)
            ->withHeaders($headers)
            ->withUri(Uri::fromEnvironment($environment));
    }
}
