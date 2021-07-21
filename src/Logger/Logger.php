<?php
declare(strict_types=1);

namespace ExtendsFramework\Logger;

use Exception;
use ExtendsFramework\Logger\Priority\PriorityInterface;
use ExtendsFramework\Logger\Writer\WriterException;
use ExtendsFramework\Logger\Writer\WriterInterface;

class Logger implements LoggerInterface
{
    /**
     * Writer queue.
     *
     * @var LoggerWriter[]
     */
    private array $writers = [];

    /**
     * Resource to write to when a logger fails.
     *
     * @var resource
     */
    private $stream;

    /**
     * Logger constructor.
     *
     * @param string|null $filename
     */
    public function __construct(string $filename = null)
    {
        $this->stream = fopen($filename ?: 'php://stderr', 'w');
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function log(string $message, PriorityInterface $priority = null, array $metaData = null): LoggerInterface
    {
        $log = new Log($message, $priority ?? null, null, $metaData ?? null);
        foreach ($this->writers as $writer) {
            try {
                $writer
                    ->getWriter()
                    ->write($log);
                if ($writer->mustInterrupt()) {
                    break;
                }
            } catch (WriterException $exception) {
                fwrite($this->stream, $exception->getMessage());
            }
        }

        return $this;
    }

    /**
     * Add writer to logger.
     *
     * When interrupt is true and the writer's write method will not throw an exception, the next writer won't be
     * called.
     *
     * @param WriterInterface $writer
     * @param bool|null       $interrupt
     *
     * @return Logger
     */
    public function addWriter(WriterInterface $writer, bool $interrupt = null): Logger
    {
        $this->writers[] = new LoggerWriter($writer, $interrupt ?: false);

        return $this;
    }
}
