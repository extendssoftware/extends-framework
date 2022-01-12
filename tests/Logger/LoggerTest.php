<?php
declare(strict_types=1);

namespace ExtendsFramework\Logger;

use ExtendsFramework\Logger\Exception\FilenameNotWritable;
use ExtendsFramework\Logger\Priority\PriorityInterface;
use ExtendsFramework\Logger\Writer\File\Exception\FileWriterFailed;
use ExtendsFramework\Logger\Writer\WriterException;
use ExtendsFramework\Logger\Writer\WriterInterface;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class LoggerTest extends TestCase
{
    /**
     * Log.
     *
     * Test that message will be logged with priority and metadata.
     *
     * @covers \ExtendsFramework\Logger\Logger::addWriter()
     * @covers \ExtendsFramework\Logger\LoggerWriter::__construct()
     * @covers \ExtendsFramework\Logger\LoggerWriter::getWriter()
     * @covers \ExtendsFramework\Logger\LoggerWriter::mustInterrupt()
     * @covers \ExtendsFramework\Logger\Logger::log()
     */
    public function testLog(): void
    {
        $priority = $this->createMock(PriorityInterface::class);

        $writer = $this->createMock(WriterInterface::class);
        $writer
            ->expects($this->once())
            ->method('write')
            ->with($this->callback(function (LogInterface $log) use ($priority) {
                $this->assertSame('Error!', $log->getMessage());
                $this->assertSame($priority, $log->getPriority());
                $this->assertSame(['foo' => 'bar'], $log->getMetaData());

                return true;
            }));

        /**
         * @var WriterInterface   $writer
         * @var PriorityInterface $priority
         */
        $logger = new Logger();
        $logger
            ->addWriter($writer)
            ->log('Error!', $priority, ['foo' => 'bar']);
    }

    /**
     * Syslog.
     *
     * Test that logger will write to streams when writer throws an exception will writing.
     *
     * @covers \ExtendsFramework\Logger\Logger::__construct()
     * @covers \ExtendsFramework\Logger\Logger::addWriter()
     * @covers \ExtendsFramework\Logger\LoggerWriter::__construct()
     * @covers \ExtendsFramework\Logger\LoggerWriter::getWriter()
     * @covers \ExtendsFramework\Logger\LoggerWriter::mustInterrupt()
     * @covers \ExtendsFramework\Logger\Logger::log()
     */
    public function testStream(): void
    {
        $root = vfsStream::setup();

        $exception = new class('Exception!') extends RuntimeException implements WriterException {
        };

        /**
         * @var FileWriterFailed $exception
         */
        $writer = $this->createMock(WriterInterface::class);
        $writer
            ->expects($this->once())
            ->method('write')
            ->willThrowException($exception);

        /**
         * @var WriterInterface    $writer
         */
        $logger = new Logger($root->url() . '/error');
        $logger
            ->addWriter($writer)
            ->log('Error!');


        $this->assertSame('Exception!', $root->getChild('error')->getContent());
    }

    /**
     * Interrupt.
     *
     * Test that writer will interrupt next writers.
     *
     * @covers \ExtendsFramework\Logger\Logger::addWriter()
     * @covers \ExtendsFramework\Logger\LoggerWriter::__construct()
     * @covers \ExtendsFramework\Logger\LoggerWriter::getWriter()
     * @covers \ExtendsFramework\Logger\LoggerWriter::mustInterrupt()
     * @covers \ExtendsFramework\Logger\Logger::log()
     */
    public function testInterrupt(): void
    {
        $priority = $this->createMock(PriorityInterface::class);

        $writer = $this->createMock(WriterInterface::class);
        $writer
            ->expects($this->once())
            ->method('write');

        /**
         * @var WriterInterface   $writer
         * @var PriorityInterface $priority
         */
        $logger = new Logger();
        $logger
            ->addWriter($writer, true)
            ->addWriter($writer)
            ->addWriter($writer)
            ->log('Error!', $priority, ['foo' => 'bar']);
    }

    /**
     * Filename not writable.
     *
     * Test that an exception will be thrown when filename can not be opened for writing.
     *
     * @covers \ExtendsFramework\Logger\Logger::__construct()
     * @covers \ExtendsFramework\Logger\Exception\FilenameNotWritable::__construct()
     */
    public function testFilenameNotReadable(): void
    {
        $this->expectException(FilenameNotWritable::class);
        $this->expectExceptionMessage('Filename "/" can not be opened for writing.');

        new Logger('/');
    }
}
