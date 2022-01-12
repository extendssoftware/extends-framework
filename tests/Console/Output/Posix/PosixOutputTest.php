<?php
declare(strict_types=1);

namespace ExtendsFramework\Console\Output\Posix;

use ExtendsFramework\Console\Formatter\Ansi\AnsiFormatter;
use ExtendsFramework\Console\Output\Exception\FilenameNotWritable;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class PosixOutputTest extends TestCase
{
    /**
     * Text.
     *
     * Test that text ('Hello world!') will be sent to output.
     *
     * @covers \ExtendsFramework\Console\Output\Posix\PosixOutput::__construct()
     * @covers \ExtendsFramework\Console\Output\Posix\PosixOutput::__destruct()
     * @covers \ExtendsFramework\Console\Output\Posix\PosixOutput::text()
     */
    public function testText(): void
    {
        $root = vfsStream::setup();

        $output = new PosixOutput(null, null, $root->url() . '/posix');
        $output->text('Hello world!');

        $this->assertEquals('Hello world!', $root->getChild('posix')->getContent());
    }

    /**
     * Formatted text.
     *
     * Text that text ('1234567890') with format (fixed with of 5) will be sent to output ('12345').
     *
     * @covers \ExtendsFramework\Console\Output\Posix\PosixOutput::__construct()
     * @covers \ExtendsFramework\Console\Output\Posix\PosixOutput::__destruct()
     * @covers \ExtendsFramework\Console\Output\Posix\PosixOutput::text()
     */
    public function testFormattedText(): void
    {
        $root = vfsStream::setup();

        $output = new PosixOutput(null, null, $root->url() . '/posix');
        $output->text('1234567890', $output->getFormatter()->setFixedWidth(5));

        $text = $root->getChild('posix')->getContent();

        $this->assertStringContainsString('12345', $text);
        $this->assertStringNotContainsString('67890', $text);
    }

    /**
     * Line.
     *
     * Test that text ('Hello world!') will be sent to output with newline character.
     *
     * @covers \ExtendsFramework\Console\Output\Posix\PosixOutput::__construct()
     * @covers \ExtendsFramework\Console\Output\Posix\PosixOutput::__destruct()
     * @covers \ExtendsFramework\Console\Output\Posix\PosixOutput::line()
     * @covers \ExtendsFramework\Console\Output\Posix\PosixOutput::text()
     * @covers \ExtendsFramework\Console\Output\Posix\PosixOutput::newLine()
     */
    public function testLine(): void
    {
        $root = vfsStream::setup();

        $output = new PosixOutput(null, null, $root->url() . '/posix');
        $output->line('Hello world!');

        $this->assertEquals('Hello world!' . "\n\r", $root->getChild('posix')->getContent());
    }

    /**
     * New line.
     *
     * Test that new line ("\n\r") will be sent to output.
     *
     * @covers \ExtendsFramework\Console\Output\Posix\PosixOutput::__construct()
     * @covers \ExtendsFramework\Console\Output\Posix\PosixOutput::__destruct()
     * @covers \ExtendsFramework\Console\Output\Posix\PosixOutput::newLine()
     * @covers \ExtendsFramework\Console\Output\Posix\PosixOutput::text()
     */
    public function testCanWriteNewLineToOutput(): void
    {
        $root = vfsStream::setup();

        $output = new PosixOutput(null, null, $root->url() . '/posix');
        $output->newLine();

        $this->assertEquals("\n\r", $root->getChild('posix')->getContent());
    }

    /**
     * Get formatter.
     *
     * Test that the default formatter (AnsiFormatter) will be returned.
     *
     * @covers \ExtendsFramework\Console\Output\Posix\PosixOutput::__construct()
     * @covers \ExtendsFramework\Console\Output\Posix\PosixOutput::__destruct()
     * @covers \ExtendsFramework\Console\Output\Posix\PosixOutput::getFormatter()
     */
    public function testGetFormatter(): void
    {
        $root = vfsStream::setup();

        $output = new PosixOutput(null, null, $root->url() . '/posix');
        $formatter = $output->getFormatter();

        $this->assertInstanceOf(AnsiFormatter::class, $formatter);
    }

    /**
     * Higher verbosity.
     *
     * Test that verbosity (3) can be set and still output text with lower verbosity (2).
     *
     * @covers \ExtendsFramework\Console\Output\Posix\PosixOutput::__construct()
     * @covers \ExtendsFramework\Console\Output\Posix\PosixOutput::__destruct()
     * @covers \ExtendsFramework\Console\Output\Posix\PosixOutput::setVerbosity()
     * @covers \ExtendsFramework\Console\Output\Posix\PosixOutput::text()
     */
    public function testHigherVerbosity(): void
    {
        $root = vfsStream::setup();

        $output = new PosixOutput(null, null, $root->url() . '/posix');
        $output
            ->setVerbosity(3)
            ->text('Hello world!', null, 2);

        $this->assertEquals('Hello world!', $root->getChild('posix')->getContent());
    }

    /**
     * Higher verbosity.
     *
     * Test that verbosity (2) can be set and don't output text with higher verbosity (3).
     *
     * @covers \ExtendsFramework\Console\Output\Posix\PosixOutput::__construct()
     * @covers \ExtendsFramework\Console\Output\Posix\PosixOutput::__destruct()
     * @covers \ExtendsFramework\Console\Output\Posix\PosixOutput::setVerbosity()
     * @covers \ExtendsFramework\Console\Output\Posix\PosixOutput::text()
     */
    public function testLowerVerbosity(): void
    {
        $root = vfsStream::setup();

        $output = new PosixOutput(null, null, $root->url() . '/posix');
        $output
            ->setVerbosity(2)
            ->text('Hello world!', null, 3);

        $this->assertEmpty($root->getChild('posix')->getContent());
    }

    /**
     * Filename not writable.
     *
     * Test that an exception will be thrown when filename can not be opened for writing.
     *
     * @covers \ExtendsFramework\Console\Output\Posix\PosixOutput::__construct()
     * @covers \ExtendsFramework\Console\Output\Exception\FilenameNotWritable::__construct()
     */
    public function testFilenameNotReadable(): void
    {
        $this->expectException(FilenameNotWritable::class);
        $this->expectExceptionMessage('Filename "/" can not be opened for writing.');

        new PosixOutput(null, null, '/');
    }
}
