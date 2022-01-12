<?php
declare(strict_types=1);

namespace ExtendsFramework\Console\Output\Posix;

use ExtendsFramework\Console\Formatter\Ansi\AnsiFormatter;
use ExtendsFramework\Console\Formatter\FormatterInterface;
use ExtendsFramework\Console\Output\OutputInterface;
use TypeError;

class PosixOutput implements OutputInterface
{
    /**
     * Resource to write to.
     *
     * @var resource
     */
    private $stream;

    /**
     * Text formatter.
     *
     * @var FormatterInterface
     */
    private $formatter;

    /**
     * Output verbosity.
     *
     * @var int
     */
    private int $verbosity;

    /**
     * PosixOutput constructor.
     *
     * @param FormatterInterface|null $formatter
     * @param int|null                $verbosity
     * @param mixed                   $stream
     *
     * @throws TypeError When stream not of type resource.
     */
    public function __construct(FormatterInterface $formatter = null, int $verbosity = null, $stream = null)
    {
        $stream = $stream ?: fopen('php://stdout', 'w');
        if (!is_resource($stream)) {
            throw new TypeError(sprintf(
                'Stream must be of type resource, %s given.',
                gettype($stream)
            ));
        }

        $this->stream = $stream;
        $this->formatter = $formatter ?? new AnsiFormatter();
        $this->verbosity = $verbosity ?? 1;
    }

    /**
     * @inheritDoc
     */
    public function text(string $text, FormatterInterface $formatter = null, int $verbosity = null): OutputInterface
    {
        if (($verbosity ?? 1) <= $this->verbosity) {
            if ($formatter) {
                $text = $formatter->create($text);
            }

            fwrite($this->stream, $text);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function line(string $text, FormatterInterface $formatter = null, int $verbosity = null): OutputInterface
    {
        return $this
            ->text($text, $formatter, $verbosity)
            ->newLine($verbosity);
    }

    /**
     * @inheritDoc
     */
    public function newLine(int $verbosity = null): OutputInterface
    {
        return $this->text("\n\r", null, $verbosity);
    }

    /**
     * @inheritDoc
     */
    public function getFormatter(): FormatterInterface
    {
        return $this->formatter;
    }

    /**
     * @inheritDoc
     */
    public function setVerbosity(int $verbosity): OutputInterface
    {
        $this->verbosity = $verbosity;

        return $this;
    }
}
