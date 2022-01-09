<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Config\Loader\File;

use ExtendsFramework\ServiceLocator\Config\Loader\LoaderInterface;

class FileLoader implements LoaderInterface
{
    /**
     * Directories.
     *
     * @var mixed[]
     */
    private array $directories = [];

    /**
     * @inheritDoc
     */
    public function load(): array
    {
        $loaded = [];
        foreach ($this->directories as [$directory, $regex]) {
            $filenames = scandir($directory);
            foreach ($filenames as $filename) {
                if (!in_array($filename, ['.', '..']) && preg_match($regex, $filename)) {
                    $loaded[] = require $directory . '/' . $filename;
                }
            }
        }

        return $loaded;
    }

    /**
     * Add glob path.
     *
     * @param string $directory
     * @param string $regex
     *
     * @return FileLoader
     */
    public function addPath(string $directory, string $regex): FileLoader
    {
        $this->directories[] = [$directory, $regex];

        return $this;
    }
}
