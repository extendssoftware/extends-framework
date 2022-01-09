<?php
declare(strict_types=1);

namespace ExtendsFramework\Shell;

use ExtendsFramework\Shell\Command\CommandInterface;

class ShellResult implements ShellResultInterface
{
    /**
     * Matched command.
     *
     * @var CommandInterface
     */
    private CommandInterface $command;

    /**
     * Parsed data for command.
     *
     * @var mixed[]
     */
    private array $data;

    /**
     * Create new shell result.
     *
     * @param CommandInterface $command
     * @param mixed[]          $data
     */
    public function __construct(CommandInterface $command, array $data)
    {
        $this->command = $command;
        $this->data = $data;
    }

    /**
     * @inheritDoc
     */
    public function getCommand(): CommandInterface
    {
        return $this->command;
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        return $this->data;
    }
}
