<?php
declare(strict_types=1);

namespace ExtendsFramework\Console\Input\Posix;

use ExtendsFramework\Console\Input\InputInterface;
use TypeError;

class PosixInput implements InputInterface
{
    /**
     * Resource to read from.
     *
     * @var resource
     */
    private $stream;

    /**
     * PosixInput constructor.
     *
     * @param mixed $stream
     *
     * @throws TypeError When stream not of type resource.
     */
    public function __construct($stream = null)
    {
        $stream = $stream ?: fopen('php://stdin', 'r');
        if (!is_resource($stream)) {
            throw new TypeError(sprintf(
                'Stream must be of type resource, %s given.',
                gettype($stream)
            ));
        }

        $this->stream = $stream;
    }

    /**
     * @inheritDoc
     */
    public function line(int $length = null): ?string
    {
        $line = fgets($this->stream, max(1, $length ?? 4096));
        if (is_string($line)) {
            $line = rtrim($line, "\n\r");
        }

        return $line ?: null;
    }

    /**
     * @inheritDoc
     */
    public function character(string $allowed = null): ?string
    {
        $character = fgetc($this->stream);
        if (is_string($character)) {
            if (is_string($allowed) && strpos($allowed, $character) === false) {
                $character = '';
            }

            $character = rtrim($character, "\n\r");
        }

        return $character ?: null;
    }
}
