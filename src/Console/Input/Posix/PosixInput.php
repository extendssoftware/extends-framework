<?php
declare(strict_types=1);

namespace ExtendsFramework\Console\Input\Posix;

use ExtendsFramework\Console\Input\InputInterface;

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
     * @param resource|null $stream
     */
    public function __construct($stream = null)
    {
        $this->stream = is_resource($stream) ? $stream : STDIN;
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
