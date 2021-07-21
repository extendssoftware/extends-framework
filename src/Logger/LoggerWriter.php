<?php
declare(strict_types=1);

namespace ExtendsFramework\Logger;

use ExtendsFramework\Logger\Writer\WriterInterface;

class LoggerWriter
{
    /**
     * The writer to log.
     *
     * @var WriterInterface
     */
    private WriterInterface $writer;

    /**
     * Whether or not the logger must be stopped.
     *
     * @var bool
     */
    private bool $interrupt;

    /**
     * Create new logger writer.s
     *
     * @param WriterInterface $writer
     * @param bool            $interrupt
     */
    public function __construct(WriterInterface $writer, bool $interrupt)
    {
        $this->writer = $writer;
        $this->interrupt = $interrupt;
    }

    /**
     * Get writer.
     *
     * @return WriterInterface
     */
    public function getWriter(): WriterInterface
    {
        return $this->writer;
    }

    /**
     * If logger must interrupt after writer.
     *
     * @return bool
     */
    public function mustInterrupt(): bool
    {
        return $this->interrupt;
    }
}
