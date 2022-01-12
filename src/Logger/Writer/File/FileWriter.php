<?php
declare(strict_types=1);

namespace ExtendsFramework\Logger\Writer\File;

use ExtendsFramework\Logger\Decorator\DecoratorInterface;
use ExtendsFramework\Logger\Filter\FilterInterface;
use ExtendsFramework\Logger\LogInterface;
use ExtendsFramework\Logger\Writer\AbstractWriter;
use ExtendsFramework\Logger\Writer\File\Exception\FileWriterFailed;
use ExtendsFramework\Logger\Writer\WriterInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorException;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class FileWriter extends AbstractWriter
{
    /**
     * Location to write file to.
     *
     * @var string
     */
    private string $location;

    /**
     * File format for date function.
     *
     * @var string
     */
    private string $fileFormat;

    /**
     * Log message format.
     *
     * @var string
     */
    private string $logFormat;

    /**
     * End of line character.
     *
     * @var string
     */
    private string $newLine;

    /**
     * FileWriter constructor.
     *
     * @param string      $location
     * @param string|null $fileFormat
     * @param string|null $logFormat
     * @param string|null $newLine
     */
    public function __construct(
        string $location,
        string $fileFormat = null,
        string $logFormat = null,
        string $newLine = null
    ) {
        $this->location = $location;
        $this->fileFormat = $fileFormat ?? 'Y-m-d';
        $this->logFormat = $logFormat ?? '{datetime} {keyword} ({value}): {message} {metaData}';
        $this->newLine = $newLine ?? PHP_EOL;
    }

    /**
     * @inheritDoc
     * @throws ServiceLocatorException
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        $writer = new FileWriter(
            /** @phpstan-ignore-next-line */
            $extra['location'],
            $extra['file_format'] ?? null,
            $extra['log_format'] ?? null,
            $extra['new_line'] ?? null
        );

        foreach ($extra['filters'] ?? [] as $config) {
            $filter = $serviceLocator->getService($config['name'], $config['options'] ?? []);

            if ($filter instanceof FilterInterface) {
                $writer->addFilter($filter);
            }
        }

        foreach ($extra['decorators'] ?? [] as $config) {
            $decorator = $serviceLocator->getService($config['name'], $config['options'] ?? []);
            if ($decorator instanceof DecoratorInterface) {
                $writer->addDecorator($decorator);
            }
        }

        return $writer;
    }

    /**
     * @inheritDoc
     */
    public function write(LogInterface $log): WriterInterface
    {
        if (!$this->filter($log)) {
            $log = $this->decorate($log);

            $metaData = $log->getMetaData() ?: null;
            if (is_array($metaData)) {
                $metaData = json_encode($metaData, JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_UNESCAPED_SLASHES);
            }

            $priority = $log->getPriority();
            $replacePairs = [
                '{datetime}' => $log
                    ->getDateTime()
                    ->format(DATE_ATOM),
                '{keyword}' => strtoupper($priority->getKeyword()),
                '{value}' => $priority->getValue(),
                '{message}' => $log->getMessage(),
                '{metaData}' => $metaData,
            ];

            $message = trim(strtr($this->logFormat, $replacePairs));
            $filename = sprintf(
                '%s/%s.log',
                rtrim($this->location, '/'),
                date($this->fileFormat)
            );

            $handle = @fopen($filename, 'ab');
            if (!is_resource($handle) || @fwrite($handle, $message . $this->newLine) === false) {
                throw new FileWriterFailed($message, $filename);
            }

            fclose($handle);
        }

        return $this;
    }
}
