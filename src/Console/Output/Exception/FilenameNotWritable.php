<?php
declare(strict_types=1);

namespace ExtendsFramework\Console\Output\Exception;

use Exception;
use ExtendsFramework\Console\Output\OutputException;

class FilenameNotWritable extends Exception implements OutputException
{
    /**
     * Filename can not be opened for writing.
     *
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        parent::__construct(sprintf(
            'Filename "%s" can not be opened for writing.',
            $filename
        ));
    }
}