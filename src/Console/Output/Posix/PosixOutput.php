<?php
declare(strict_types=1);

namespace ExtendsFramework\Console\Output\Posix;

use ExtendsFramework\Console\Formatter\Ansi\AnsiFormatter;
use ExtendsFramework\Console\Formatter\FormatterInterface;
use ExtendsFramework\Console\Output\OutputInterface;

class PosixOutput implements OutputInterface
{
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
     * Resource to write to.
     *
     * @var resource
     */
    private $stream;

    /**
     * PosixOutput constructor.
     *
     * @param FormatterInterface|null $formatter
     * @param int|null                $verbosity
     * @param resource|null           $stream
     */
    public function __construct(FormatterInterface $formatter = null, int $verbosity = null, $stream = null)
    {
        $this->formatter = $formatter ?? new AnsiFormatter();
        $this->verbosity = $verbosity ?? 1;
        $this->stream = is_resource($stream) ? $stream : STDOUT;
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
