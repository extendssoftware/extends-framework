<?php
declare(strict_types=1);

namespace ExtendsFramework\Console\Input\Posix;

use ExtendsFramework\Console\Input\InputInterface;

class PosixInput implements InputInterface
{
    /**
     * Resource to read from.
     *
     * @var resource|false
     */
    private $stream;

    /**
     * PosixInput constructor.
     *
     * @param string|null $filename
     */
    public function __construct(string $filename = null)
    {
        $this->stream = fopen($filename ?: 'php://stdin', 'r');
    }

    /**
     * @inheritDoc
     */
    public function line(int $length = null): ?string
    {
        if (is_resource($this->stream)) {
            $line = fgets($this->stream, max(1, $length ?? 4096));
            if (is_string($line)) {
                return rtrim($line, "\n\r") ?: null;
            }
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function character(string $allowed = null): ?string
    {
        if (is_resource($this->stream)) {
            $character = fgetc($this->stream);
            if (is_string($character)) {
                if (is_string($allowed) && strpos($allowed, $character) === false) {
                    $character = '';
                }

                return rtrim($character, "\n\r") ?: null;
            }
        }

        return null;
    }
}
