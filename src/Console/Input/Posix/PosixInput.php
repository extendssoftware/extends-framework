<?php
declare(strict_types=1);

namespace ExtendsFramework\Console\Input\Posix;

use ExtendsFramework\Console\Input\Exception\FilenameNotReadable;
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
     * @param string|null $filename
     *
     * @throws FilenameNotReadable When filename is not a resource or readable.
     */
    public function __construct(string $filename = null)
    {
        $filename = $filename ?: 'php://stdin';
        $stream = @fopen($filename, 'r');
        if (!is_resource($stream)) {
            throw new FilenameNotReadable($filename);
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
