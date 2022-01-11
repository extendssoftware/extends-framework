<?php
declare(strict_types=1);

namespace ExtendsFramework\Shell\About;

interface AboutInterface
{
    /**
     * Get name of the shell.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get program to run shell.
     *
     * @return string
     */
    public function getProgram(): string;

    /**
     * Get shell version.
     *
     * @return string
     */
    public function getVersion(): string;
}
