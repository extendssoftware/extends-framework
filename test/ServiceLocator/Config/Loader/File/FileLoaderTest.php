<?php
declare(strict_types=1);

namespace ExtendsFramework\ServiceLocator\Config\Loader\File;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class FileLoaderTest extends TestCase
{
    /**
     * Load.
     *
     * Test that added path will be loaded and array is returned.
     *
     * @covers \ExtendsFramework\ServiceLocator\Config\Loader\File\FileLoader::addPath()
     * @covers \ExtendsFramework\ServiceLocator\Config\Loader\File\FileLoader::load()
     */
    public function testLoad(): void
    {
        $root = vfsStream::setup('root', null, [
            'config' => [
                'global.php' => "<?php return ['global' => ['baz' => 'baz']];",
                'local.dist.php' => "<?php return ['quux' => 'quux'];",
                'local.php' => "<?php return ['local' => ['qux' => 'qux'], 'global' => ['foo' => 'bar']];",
            ],
        ]);

        $loader = new FileLoader();
        $loader->addPath($root->url() . '/config/', '/(.*)?(local|global)\.php/i');
        $loaded = $loader->load();

        $this->assertSame([
            [
                'global' => [
                    'baz' => 'baz',
                ],
            ],
            [
                'local' => [
                    'qux' => 'qux',
                ],
                'global' => [
                    'foo' => 'bar',
                ],
            ],
        ], $loaded);
    }
}
