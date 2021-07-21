<?php
declare(strict_types=1);

namespace ExtendsFramework\Logger\Writer\File;

use DateTime;
use ExtendsFramework\Logger\Decorator\DecoratorInterface;
use ExtendsFramework\Logger\Filter\FilterInterface;
use ExtendsFramework\Logger\LogInterface;
use ExtendsFramework\Logger\Priority\PriorityInterface;
use ExtendsFramework\Logger\Writer\File\Exception\FileWriterFailed;
use ExtendsFramework\Logger\Writer\WriterInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class FileWriterTest extends TestCase
{
    /**
     * Write.
     *
     * Test that writer will writer log message to file.
     *
     * @covers \ExtendsFramework\Logger\Writer\File\FileWriter::__construct()
     * @covers \ExtendsFramework\Logger\Writer\AbstractWriter::addFilter()
     * @covers \ExtendsFramework\Logger\Writer\AbstractWriter::addDecorator()
     * @covers \ExtendsFramework\Logger\Writer\File\FileWriter::write()
     * @covers \ExtendsFramework\Logger\Writer\AbstractWriter::filter()
     * @covers \ExtendsFramework\Logger\Writer\AbstractWriter::decorate()
     */
    public function testWrite(): void
    {
        $root = vfsStream::setup('root', null, [
            'log' => [],
        ]);

        $priority = $this->createMock(PriorityInterface::class);
        $priority
            ->expects($this->once())
            ->method('getKeyword')
            ->willReturn('crit');

        $priority
            ->expects($this->once())
            ->method('getValue')
            ->willReturn(2);

        $dateTime = $this->createMock(DateTime::class);
        $dateTime
            ->expects($this->once())
            ->method('format')
            ->willReturn('2017-10-13T14:50:28+00:00');

        $log = $this->createMock(LogInterface::class);
        $log
            ->expects($this->once())
            ->method('getMetaData')
            ->willReturn(['foo' => 'bar']);

        $log
            ->expects($this->once())
            ->method('getPriority')
            ->willReturn($priority);

        $log
            ->expects($this->once())
            ->method('getMessage')
            ->willReturn('Exceptional error!');

        $log
            ->expects($this->once())
            ->method('getDateTime')
            ->willReturn($dateTime);

        $filter = $this->createMock(FilterInterface::class);
        $filter
            ->expects($this->once())
            ->method('filter')
            ->with($log)
            ->willReturn(false);

        $decorator = $this->createMock(DecoratorInterface::class);
        $decorator
            ->expects($this->once())
            ->method('decorate')
            ->with($log)
            ->willReturnArgument(0);

        /**
         * @var LogInterface       $log
         * @var FilterInterface    $filter
         * @var DecoratorInterface $decorator
         */
        $writer = new FileWriter($root->url() . '/log');
        $result = $writer
            ->addFilter($filter)
            ->addDecorator($decorator)
            ->write($log);

        $filename = 'log/' . date('Y-m-d') . '.log';

        $this->assertIsObject($result);
        $this->assertTrue($root->hasChild($filename));
        $this->assertSame(
            '2017-10-13T14:50:28+00:00 CRIT (2): Exceptional error! {"foo":"bar"}' . PHP_EOL,
            $root->getChild($filename)->getContent()
        );
    }

    /**
     * Filter.
     *
     * Test that custom format and new line character are used for write.
     *
     * @covers \ExtendsFramework\Logger\Writer\File\FileWriter::__construct()
     * @covers \ExtendsFramework\Logger\Writer\AbstractWriter::addFilter()
     * @covers \ExtendsFramework\Logger\Writer\AbstractWriter::addDecorator()
     * @covers \ExtendsFramework\Logger\Writer\File\FileWriter::write()
     * @covers \ExtendsFramework\Logger\Writer\AbstractWriter::filter()
     */
    public function testCustomFormat(): void
    {
        $root = vfsStream::setup('root', null, [
            'log' => [],
        ]);

        $priority = $this->createMock(PriorityInterface::class);
        $priority
            ->expects($this->once())
            ->method('getKeyword')
            ->willReturn('crit');

        $priority
            ->expects($this->once())
            ->method('getValue')
            ->willReturn(2);

        $dateTime = $this->createMock(DateTime::class);
        $dateTime
            ->expects($this->once())
            ->method('format')
            ->willReturn('2017-10-13T14:50:28+00:00');

        $log = $this->createMock(LogInterface::class);
        $log
            ->expects($this->once())
            ->method('getMetaData')
            ->willReturn(['foo' => 'bar']);

        $log
            ->expects($this->once())
            ->method('getPriority')
            ->willReturn($priority);

        $log
            ->expects($this->once())
            ->method('getMessage')
            ->willReturn('Exceptional error!');

        $log
            ->expects($this->once())
            ->method('getDateTime')
            ->willReturn($dateTime);

        $filter = $this->createMock(FilterInterface::class);
        $filter
            ->expects($this->once())
            ->method('filter')
            ->with($log)
            ->willReturn(false);

        $decorator = $this->createMock(DecoratorInterface::class);
        $decorator
            ->expects($this->once())
            ->method('decorate')
            ->with($log)
            ->willReturnArgument(0);

        /**
         * @var LogInterface       $log
         * @var FilterInterface    $filter
         * @var DecoratorInterface $decorator
         */
        $writer = new FileWriter(
            $root->url() . '/log',
            'd-m-Y',
            '{keyword} ({value}): {message} {metaData}, {datetime}',
            "\n\r"
        );
        $result = $writer
            ->addFilter($filter)
            ->addDecorator($decorator)
            ->write($log);

        $filename = 'log/' . date('d-m-Y') . '.log';

        $this->assertIsObject($result);
        $this->assertTrue($root->hasChild($filename));
        $this->assertSame(
            'CRIT (2): Exceptional error! {"foo":"bar"}, 2017-10-13T14:50:28+00:00' . "\n\r",
            $root->getChild($filename)->getContent()
        );
    }

    /**
     * Filter.
     *
     * Test that writer will not write when log is filtered.
     *
     * @covers \ExtendsFramework\Logger\Writer\File\FileWriter::__construct()
     * @covers \ExtendsFramework\Logger\Writer\AbstractWriter::addFilter()
     * @covers \ExtendsFramework\Logger\Writer\AbstractWriter::addDecorator()
     * @covers \ExtendsFramework\Logger\Writer\File\FileWriter::write()
     * @covers \ExtendsFramework\Logger\Writer\AbstractWriter::filter()
     */
    public function testFilter(): void
    {
        $root = vfsStream::setup('root', null, [
            'log' => [],
        ]);

        $log = $this->createMock(LogInterface::class);

        $filter = $this->createMock(FilterInterface::class);
        $filter
            ->expects($this->once())
            ->method('filter')
            ->with($log)
            ->willReturn(true);

        $decorator = $this->createMock(DecoratorInterface::class);
        $decorator
            ->expects($this->never())
            ->method('decorate');

        /**
         * @var LogInterface    $log
         * @var FilterInterface $filter
         */
        $writer = new FileWriter('/var/log/extends');
        $result = $writer
            ->addFilter($filter)
            ->write($log);

        $this->assertIsObject($result);
        $this->assertFalse($root->getChild('log')->hasChildren());
    }

    /**
     * Write failed.
     *
     * Test that when writing to file fails and exception will be thrown.
     *
     * @covers \ExtendsFramework\Logger\Writer\File\FileWriter::__construct()
     * @covers \ExtendsFramework\Logger\Writer\File\FileWriter::write()
     * @covers \ExtendsFramework\Logger\Writer\AbstractWriter::filter()
     * @covers \ExtendsFramework\Logger\Writer\AbstractWriter::decorate()
     * @covers \ExtendsFramework\Logger\Writer\File\Exception\FileWriterFailed::__construct()
     */
    public function testWriteFailed(): void
    {
        $this->expectException(FileWriterFailed::class);
        $this->expectExceptionMessage('Failed to write message "2017-10-13T14:50:28+00:00 CRIT (2): Exceptional error! '
            . '{"foo":"bar"}" to file "vfs://root/log/'. date('Y-m-d') . '.log".');

        $root = vfsStream::setup();

        $priority = $this->createMock(PriorityInterface::class);
        $priority
            ->expects($this->once())
            ->method('getKeyword')
            ->willReturn('crit');

        $priority
            ->expects($this->once())
            ->method('getValue')
            ->willReturn(2);

        $dateTime = $this->createMock(DateTime::class);
        $dateTime
            ->expects($this->once())
            ->method('format')
            ->willReturn('2017-10-13T14:50:28+00:00');

        $log = $this->createMock(LogInterface::class);
        $log
            ->expects($this->once())
            ->method('getMetaData')
            ->willReturn(['foo' => 'bar']);

        $log
            ->expects($this->once())
            ->method('getPriority')
            ->willReturn($priority);

        $log
            ->expects($this->once())
            ->method('getMessage')
            ->willReturn('Exceptional error!');

        $log
            ->expects($this->once())
            ->method('getDateTime')
            ->willReturn($dateTime);

        /**
         * @var LogInterface $log
         */
        $writer = new FileWriter($root->url() . '/log');
        $writer->write($log);
    }

    /**
     * Factory.
     *
     * Test that factory methods returns an instance of WriterInterface.
     *
     * @covers \ExtendsFramework\Logger\Writer\File\FileWriter::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->exactly(2))
            ->method('getService')
            ->withConsecutive(
                [FilterInterface::class],
                [DecoratorInterface::class]
            )
            ->willReturnOnConsecutiveCalls(
                $this->createMock(FilterInterface::class),
                $this->createMock(DecoratorInterface::class)
            );

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $writer = FileWriter::factory(FileWriter::class, $serviceLocator, [
            'location' => '/var/log/extends',
            'file_format' => '',
            'log_format' => '',
            'new_line' => '',
            'filters' => [
                [
                    'name' => FilterInterface::class,
                ],
            ],
            'decorators' => [
                [
                    'name' => DecoratorInterface::class,
                ],
            ],
        ]);

        $this->assertInstanceOf(WriterInterface::class, $writer);
    }
}
