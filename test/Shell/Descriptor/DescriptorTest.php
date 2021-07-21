<?php
declare(strict_types=1);

namespace ExtendsFramework\Shell\Descriptor;

use Exception;
use ExtendsFramework\Console\Output\OutputInterface;
use ExtendsFramework\Shell\About\AboutInterface;
use ExtendsFramework\Shell\Command\CommandInterface;
use ExtendsFramework\Shell\Definition\DefinitionInterface;
use ExtendsFramework\Shell\Definition\Operand\OperandInterface;
use ExtendsFramework\Shell\Definition\Option\OptionInterface;
use PHPUnit\Framework\TestCase;
use Throwable;

class DescriptorTest extends TestCase
{
    /**
     * Shell short.
     *
     * Test that descriptor can describe shell (short).
     *
     * @covers \ExtendsFramework\Shell\Descriptor\Descriptor::__construct()
     * @covers \ExtendsFramework\Shell\Descriptor\Descriptor::shell()
     */
    public function testShellShort(): void
    {
        $output = $this->createMock(OutputInterface::class);
        $output
            ->expects($this->once())
            ->method('newLine')
            ->willReturnSelf();

        $output
            ->expects($this->once())
            ->method('line')
            ->with("See 'extends --help' for more information about available commands and options.");

        $definition = $this->createMock(DefinitionInterface::class);

        $about = $this->createMock(AboutInterface::class);
        $about
            ->method('getProgram')
            ->willReturn('extends');

        /**
         * @var DefinitionInterface $definition
         * @var AboutInterface      $about
         */
        $descriptor = new Descriptor($output);
        $instance = $descriptor->shell($about, $definition, [], true);

        $this->assertSame($descriptor, $instance);
    }

    /**
     * Shell long.
     *
     * Test that descriptor can describe shell (long).
     *
     * @covers \ExtendsFramework\Shell\Descriptor\Descriptor::__construct()
     * @covers \ExtendsFramework\Shell\Descriptor\Descriptor::shell()
     * @covers \ExtendsFramework\Shell\Descriptor\Descriptor::getOptionNotation()
     */
    public function testShellLong(): void
    {
        $output = $this->createMock(OutputInterface::class);

        $output
            ->expects($this->exactly(7))
            ->method('newLine')
            ->willReturnSelf();

        $output
            ->expects($this->exactly(8))
            ->method('line')
            ->withConsecutive(
                ['Extends Framework Console (version 0.1)'],
                ['Usage:'],
                ['<command> [<arguments>] [<options>]'],
                ['Commands:'],
                ['Do some fancy task!'],
                ['Options:'],
                ['Show help.'],
                ["See 'extends <command> --help' for more information about a command."]
            )
            ->willReturnSelf();

        $output
            ->expects($this->exactly(3))
            ->method('text')
            ->withConsecutive(
                ['extends '],
                ['do.task'],
                ['-h=|--help=']
            )
            ->willReturnSelf();

        $option = $this->createMock(OptionInterface::class);
        $option
            ->expects($this->once())
            ->method('getShort')
            ->willReturn('h');

        $option
            ->expects($this->once())
            ->method('getLong')
            ->willReturn('help');

        $option
            ->expects($this->once())
            ->method('isFlag')
            ->willReturn(false);

        $option
            ->expects($this->once())
            ->method('getDescription')
            ->willReturn('Show help.');

        $definition = $this->createMock(DefinitionInterface::class);
        $definition
            ->expects($this->once())
            ->method('getOptions')
            ->willReturn([
                $option,
            ]);

        $command = $this->createMock(CommandInterface::class);
        $command
            ->expects($this->once())
            ->method('getName')
            ->willReturn('do.task');

        $command
            ->expects($this->once())
            ->method('getDescription')
            ->willReturn('Do some fancy task!');

        $about = $this->createMock(AboutInterface::class);
        $about
            ->method('getName')
            ->willReturn('Extends Framework Console');

        $about
            ->method('getProgram')
            ->willReturn('extends');

        $about
            ->method('getVersion')
            ->willReturn('0.1');

        /**
         * @var DefinitionInterface $definition
         * @var AboutInterface      $about
         */
        $descriptor = new Descriptor($output);
        $instance = $descriptor->shell($about, $definition, [
            $command,
        ]);

        $this->assertSame($descriptor, $instance);
    }

    /**
     * Shell long without commands.
     *
     * Test that descriptor can describe shell (long) and will show a dash when no commands are defined.
     *
     * @covers \ExtendsFramework\Shell\Descriptor\Descriptor::__construct()
     * @covers \ExtendsFramework\Shell\Descriptor\Descriptor::shell()
     * @covers \ExtendsFramework\Shell\Descriptor\Descriptor::getOptionNotation()
     */
    public function testShellLongWithoutCommands(): void
    {
        $output = $this->createMock(OutputInterface::class);

        $output
            ->expects($this->exactly(7))
            ->method('newLine')
            ->willReturnSelf();

        $output
            ->expects($this->exactly(8))
            ->method('line')
            ->withConsecutive(
                ['Extends Framework Console (version 0.1)'],
                ['Usage:'],
                ['<command> [<arguments>] [<options>]'],
                ['Commands:'],
                ['No commands defined.'],
                ['Options:'],
                ['Show help.'],
                ["See 'extends <command> --help' for more information about a command."]
            )
            ->willReturnSelf();

        $output
            ->expects($this->exactly(2))
            ->method('text')
            ->withConsecutive(
                ['extends '],
                ['-h=|--help=']
            )
            ->willReturnSelf();

        $option = $this->createMock(OptionInterface::class);
        $option
            ->expects($this->once())
            ->method('getShort')
            ->willReturn('h');

        $option
            ->expects($this->once())
            ->method('getLong')
            ->willReturn('help');

        $option
            ->expects($this->once())
            ->method('isFlag')
            ->willReturn(false);

        $option
            ->expects($this->once())
            ->method('getDescription')
            ->willReturn('Show help.');

        $definition = $this->createMock(DefinitionInterface::class);
        $definition
            ->expects($this->once())
            ->method('getOptions')
            ->willReturn([
                $option,
            ]);

        $about = $this->createMock(AboutInterface::class);
        $about
            ->method('getName')
            ->willReturn('Extends Framework Console');

        $about
            ->method('getProgram')
            ->willReturn('extends');

        $about
            ->method('getVersion')
            ->willReturn('0.1');

        /**
         * @var DefinitionInterface $definition
         * @var AboutInterface      $about
         */
        $descriptor = new Descriptor($output);
        $instance = $descriptor->shell($about, $definition, []);

        $this->assertSame($descriptor, $instance);
    }

    /**
     * Command short.
     *
     * Test that descriptor can describe command (short).
     *
     * @covers \ExtendsFramework\Shell\Descriptor\Descriptor::__construct()
     * @covers \ExtendsFramework\Shell\Descriptor\Descriptor::command()
     */
    public function testCommandShort(): void
    {
        $output = $this->createMock(OutputInterface::class);

        $output
            ->expects($this->exactly(1))
            ->method('newLine')
            ->willReturnSelf();

        $output
            ->expects($this->once())
            ->method('line')
            ->with("See 'extends do.task --help' for more information about the command.")
            ->willReturnSelf();

        $command = $this->createMock(CommandInterface::class);
        $command
            ->expects($this->once())
            ->method('getName')
            ->willReturn('do.task');

        $about = $this->createMock(AboutInterface::class);
        $about
            ->method('getName')
            ->willReturn('Extends Framework Console');

        $about
            ->method('getProgram')
            ->willReturn('extends');

        $about
            ->method('getVersion')
            ->willReturn('0.1');

        /**
         * @var CommandInterface $command
         * @var AboutInterface   $about
         */
        $descriptor = new Descriptor($output);
        $instance = $descriptor->command($about, $command, true);

        $this->assertSame($descriptor, $instance);
    }

    /**
     * Command long.
     *
     * Test that descriptor can describe command (long).
     *
     * @covers \ExtendsFramework\Shell\Descriptor\Descriptor::__construct()
     * @covers \ExtendsFramework\Shell\Descriptor\Descriptor::command()
     * @covers \ExtendsFramework\Shell\Descriptor\Descriptor::getOptionNotation()
     */
    public function testCommandLong(): void
    {
        $output = $this->createMock(OutputInterface::class);

        $output
            ->expects($this->exactly(5))
            ->method('newLine')
            ->willReturnSelf();

        $output
            ->expects($this->exactly(6))
            ->method('line')
            ->withConsecutive(
                ['Extends Framework Console (version 0.1)'],
                ['Usage:'],
                ['[<options>] '],
                ['Options:'],
                ['Show option.'],
                ["See 'extends --help' for more information about this shell and default options."]
            )
            ->willReturnSelf();

        $output
            ->expects($this->exactly(4))
            ->method('text')
            ->withConsecutive(
                ['extends '],
                ['do.task '],
                ['<name> '],
                ['-o+|--option+']
            )
            ->willReturnSelf();

        $operand = $this->createMock(OperandInterface::class);
        $operand
            ->expects($this->once())
            ->method('getName')
            ->willReturn('name');

        $option = $this->createMock(OptionInterface::class);
        $option
            ->expects($this->once())
            ->method('getShort')
            ->willReturn('o');

        $option
            ->expects($this->once())
            ->method('getLong')
            ->willReturn('option');

        $option
            ->expects($this->once())
            ->method('isFlag')
            ->willReturn(true);

        $option
            ->expects($this->once())
            ->method('isMultiple')
            ->willReturn(true);

        $option
            ->expects($this->once())
            ->method('getDescription')
            ->willReturn('Show option.');

        $definition = $this->createMock(DefinitionInterface::class);
        $definition
            ->expects($this->once())
            ->method('getOptions')
            ->willReturn([
                $option,
            ]);

        $definition
            ->expects($this->once())
            ->method('getOperands')
            ->willReturn([
                $operand,
            ]);

        $command = $this->createMock(CommandInterface::class);
        $command
            ->expects($this->once())
            ->method('getName')
            ->willReturn('do.task');

        $command
            ->expects($this->once())
            ->method('getDefinition')
            ->willReturn($definition);

        $about = $this->createMock(AboutInterface::class);
        $about
            ->method('getName')
            ->willReturn('Extends Framework Console');

        $about
            ->method('getProgram')
            ->willReturn('extends');

        $about
            ->method('getVersion')
            ->willReturn('0.1');

        /**
         * @var CommandInterface $command
         * @var AboutInterface   $about
         */
        $descriptor = new Descriptor($output);
        $instance = $descriptor->command($about, $command);

        $this->assertSame($descriptor, $instance);
    }

    /**
     * Command long without operands and options.
     *
     * Test that descriptor can describe command (long) without operands and options.
     *
     * @covers \ExtendsFramework\Shell\Descriptor\Descriptor::__construct()
     * @covers \ExtendsFramework\Shell\Descriptor\Descriptor::command()
     * @covers \ExtendsFramework\Shell\Descriptor\Descriptor::getOptionNotation()
     */
    public function testCommandLongWithoutOperandsAndOptions(): void
    {
        $output = $this->createMock(OutputInterface::class);

        $output
            ->expects($this->exactly(4))
            ->method('newLine')
            ->willReturnSelf();

        $output
            ->expects($this->exactly(3))
            ->method('line')
            ->withConsecutive(
                ['Extends Framework Console (version 0.1)'],
                ['Usage:'],
                ["See 'extends --help' for more information about this shell and default options."]
            )
            ->willReturnSelf();

        $output
            ->expects($this->exactly(2))
            ->method('text')
            ->withConsecutive(
                ['extends '],
                ['do.task ']
            )
            ->willReturnSelf();

        $definition = $this->createMock(DefinitionInterface::class);
        $definition
            ->expects($this->once())
            ->method('getOptions')
            ->willReturn([]);

        $definition
            ->expects($this->once())
            ->method('getOperands')
            ->willReturn([]);

        $command = $this->createMock(CommandInterface::class);
        $command
            ->expects($this->once())
            ->method('getName')
            ->willReturn('do.task');

        $command
            ->expects($this->once())
            ->method('getDefinition')
            ->willReturn($definition);

        $about = $this->createMock(AboutInterface::class);
        $about
            ->method('getName')
            ->willReturn('Extends Framework Console');

        $about
            ->method('getProgram')
            ->willReturn('extends');

        $about
            ->method('getVersion')
            ->willReturn('0.1');

        /**
         * @var CommandInterface $command
         * @var AboutInterface   $about
         */
        $descriptor = new Descriptor($output);
        $instance = $descriptor->command($about, $command);

        $this->assertSame($descriptor, $instance);
    }

    /**
     * Suggest.
     *
     * Test that descriptor can suggest.
     *
     * @covers \ExtendsFramework\Shell\Descriptor\Descriptor::__construct()
     * @covers \ExtendsFramework\Shell\Descriptor\Descriptor::suggest()
     */
    public function testSuggest(): void
    {
        $output = $this->createMock(OutputInterface::class);

        $output
            ->expects($this->once())
            ->method('newLine')
            ->willReturnSelf();

        $output
            ->expects($this->once())
            ->method('line')
            ->with('"?')
            ->willReturnSelf();

        $output
            ->expects($this->exactly(2))
            ->method('text')
            ->withConsecutive(
                ['Did you mean "'],
                ['do.task']
            )
            ->willReturnSelf();

        $command = $this->createMock(CommandInterface::class);
        $command
            ->expects($this->once())
            ->method('getName')
            ->willReturn('do.task');

        /**
         * @var OutputInterface  $output
         * @var CommandInterface $command
         */
        $descriptor = new Descriptor($output);
        $instance = $descriptor->suggest($command);

        $this->assertSame($descriptor, $instance);
    }

    /**
     * Exception.
     *
     * Test that descriptor can describe exception.
     *
     * @covers \ExtendsFramework\Shell\Descriptor\Descriptor::__construct()
     * @covers \ExtendsFramework\Shell\Descriptor\Descriptor::exception()
     */
    public function testException(): void
    {
        $output = $this->createMock(OutputInterface::class);

        $output
            ->expects($this->once())
            ->method('line')
            ->with('Random exception message!')
            ->willReturnSelf();

        /**
         * @var OutputInterface $output
         * @var Throwable       $exception
         */
        $descriptor = new Descriptor($output);
        $instance = $descriptor->exception(new Exception('Random exception message!'));

        $this->assertSame($descriptor, $instance);
    }

    /**
     * Verbosity.
     *
     * Set verbosity for output to 3.
     *
     * @covers \ExtendsFramework\Shell\Descriptor\Descriptor::__construct()
     * @covers \ExtendsFramework\Shell\Descriptor\Descriptor::setVerbosity()
     */
    public function testVerbosity(): void
    {
        $output = $this->createMock(OutputInterface::class);
        $output
            ->expects($this->once())
            ->method('setVerbosity')
            ->with(3)
            ->willReturnSelf();

        /**
         * @var OutputInterface     $output
         * @var DefinitionInterface $definition
         */
        $descriptor = new Descriptor($output);
        $instance = $descriptor->setVerbosity(3);

        $this->assertSame($descriptor, $instance);
    }
}
