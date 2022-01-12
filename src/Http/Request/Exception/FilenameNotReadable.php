<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Request\Exception;

use Exception;
use ExtendsFramework\Console\Input\InputException;

class FilenameNotReadable extends Exception implements InputException
{
    /**
     * Filename can not be opened for reading.
     *
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        parent::__construct(sprintf(
            'Filename "%s" can not be opened for reading.',
            $filename
        ));
    }
}
