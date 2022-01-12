<?php
declare(strict_types=1);

namespace ExtendsFramework\Http\Request;

use ExtendsFramework\Console\Input\Posix\PosixInput;
use ExtendsFramework\Http\Request\Exception\InvalidRequestBody;
use ExtendsFramework\Http\Request\Uri\UriInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use TypeError;

class RequestTest extends TestCase
{
    /**
     * Default $_SERVER global.
     *
     * @var array
     */
    protected static $defaultServer;

    /**
     * Save default $_SERVER global.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        static::$defaultServer = $_SERVER;
    }

    /**
     * Reset $_SERVER global.
     *
     * @return void
     */
    public function tearDown(): void
    {
        $_SERVER = static::$defaultServer;
    }

    /**
     * Get methods.
     *
     * Test that get methods will return the correct php://input abd $_SERVER values.
     *
     * @covers \ExtendsFramework\Http\Request\Request::fromEnvironment()
     * @covers \ExtendsFramework\Http\Request\Request::getAttributes()
     * @covers \ExtendsFramework\Http\Request\Request::getBody()
     * @covers \ExtendsFramework\Http\Request\Request::getHeaders()
     * @covers \ExtendsFramework\Http\Request\Request::getMethod()
     * @covers \ExtendsFramework\Http\Request\Request::getUri()
     */
    public function testCanCreateNewInstanceFromEnvironment(): void
    {
        $body = [
            'foo' => 'qux',
        ];

        $root = vfsStream::setup('root', null, [
            'input' => json_encode($body),
        ]);

        $environment['HTTP_HOST'] = 'www.example.com';
        $environment['SERVER_PORT'] = 80;
        $environment['REQUEST_METHOD'] = 'POST';
        $environment['QUERY_STRING'] = 'baz=qux';
        $environment['REQUEST_URI'] = '/foo/bar';
        $environment['HTTP_CONTENT_TYPE'] = 'application/json';

        $request = Request::fromEnvironment($environment, fopen($root->url() . '/input', 'r'));

        $this->assertSame([], $request->getAttributes());
        $this->assertEquals((object)$body, $request->getBody());
        $this->assertSame([
            'Host' => 'www.example.com',
            'Content-Type' => 'application/json',
        ], $request->getHeaders());
        $this->assertSame('POST', $request->getMethod());
        $this->assertIsObject($request->getUri());
    }

    /**
     * With methods.
     *
     * Test that with methods will set the correct value.
     *
     * @covers \ExtendsFramework\Http\Request\Request::withAttributes()
     * @covers \ExtendsFramework\Http\Request\Request::withBody()
     * @covers \ExtendsFramework\Http\Request\Request::withHeaders()
     * @covers \ExtendsFramework\Http\Request\Request::withMethod()
     * @covers \ExtendsFramework\Http\Request\Request::withUri()
     * @covers \ExtendsFramework\Http\Request\Request::getAttributes()
     * @covers \ExtendsFramework\Http\Request\Request::getAttribute()
     * @covers \ExtendsFramework\Http\Request\Request::getBody()
     * @covers \ExtendsFramework\Http\Request\Request::getHeaders()
     * @covers \ExtendsFramework\Http\Request\Request::getHeader()
     * @covers \ExtendsFramework\Http\Request\Request::getMethod()
     * @covers \ExtendsFramework\Http\Request\Request::getUri()
     */
    public function testWithMethods(): void
    {
        $uri = $this->createMock(UriInterface::class);

        /**
         * @var UriInterface $uri
         */
        $request = (new Request())
            ->withAttributes(['foo' => 'bar'])
            ->withBody(['baz' => 'qux'])
            ->withHeaders(['qux' => 'quux'])
            ->withMethod('POST')
            ->withUri($uri);

        $this->assertSame(['foo' => 'bar'], $request->getAttributes());
        $this->assertSame(['baz' => 'qux'], $request->getBody());
        $this->assertSame(['qux' => 'quux'], $request->getHeaders());
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame($uri, $request->getUri());
        $this->assertSame('quux', $request->getHeader('qux'));
        $this->assertSame('bar', $request->getAttribute('foo'));

        // Default return values.
        $this->assertSame('qux', $request->getHeader('bar', 'qux'));
        $this->assertSame('quux', $request->getAttribute('bar', 'quux'));
    }

    /**
     * And attribute.
     *
     * Test that a single attribute parameter can be set.
     *
     * @covers \ExtendsFramework\Http\Request\Request::andAttribute()
     * @covers \ExtendsFramework\Http\Request\Request::getAttributes()
     */
    public function testCanMergeWithAttribute(): void
    {
        $request = (new Request())
            ->andAttribute('foo', 'bar')
            ->andAttribute('qux', 'quux');

        $this->assertSame([
            'foo' => 'bar',
            'qux' => 'quux',
        ], $request->getAttributes());
    }

    /**
     * And header.
     *
     * Test that a single header parameter can be set.
     *
     * @covers \ExtendsFramework\Http\Request\Request::andHeader()
     * @covers \ExtendsFramework\Http\Request\Request::getHeaders()
     */
    public function testAndHeader(): void
    {
        $request = (new Request())
            ->andHeader('foo', 'bar')
            ->andHeader('foo', 'baz')
            ->andHeader('qux', 'quux');

        $this->assertSame([
            'foo' => [
                'bar',
                'baz',
            ],
            'qux' => 'quux',
        ], $request->getHeaders());
    }

    /**
     * With headers.
     *
     * Test that already set header will be overwritten.
     *
     * @covers \ExtendsFramework\Http\Request\Request::andHeader()
     * @covers \ExtendsFramework\Http\Request\Request::withHeader()
     * @covers \ExtendsFramework\Http\Request\Request::getHeaders()
     * @covers \ExtendsFramework\Http\Request\Request::getHeader()
     */
    public function testWithHeader(): void
    {
        $request = (new Request())
            ->andHeader('foo', 'bar')
            ->andHeader('foo', 'baz')
            ->withHeader('foo', 'qux')
            ->andHeader('qux', 'quux');

        $this->assertSame([
            'foo' => 'qux',
            'qux' => 'quux',
        ], $request->getHeaders());
    }

    /**
     * Get URI.
     *
     * Test that default URI object will be returned.
     *
     * @covers \ExtendsFramework\Http\Request\Request::getUri()
     */
    public function testGetUri(): void
    {
        $uri = (new Request())->getUri();

        $this->assertIsObject($uri);
    }

    /**
     * Invalid body.
     *
     * Test that invalid body can not be parsed and a exception will be thrown.
     *
     * @covers \ExtendsFramework\Http\Request\Request::fromEnvironment()
     * @covers \ExtendsFramework\Http\Request\Exception\InvalidRequestBody::__construct()
     */
    public function testInvalidBody(): void
    {
        $this->expectException(InvalidRequestBody::class);
        $this->expectExceptionMessage('Invalid JSON for request body, got parse error "Syntax error".');

        $root = vfsStream::setup('root', null, [
            'input' => '{"foo":"qux"',
        ]);

        Request::fromEnvironment($_SERVER, fopen($root->url() . '/input', 'r'));
    }

    /**
     * Empty body.
     *
     * Test that empty body is allowed for request.
     *
     * @covers \ExtendsFramework\Http\Request\Request::fromEnvironment()
     */
    public function testEmptyBody(): void
    {
        $root = vfsStream::setup('root', null, [
            'input' => '',
        ]);

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['HTTP_HOST'] = 'www.extends.nl';
        $_SERVER['SERVER_PORT'] = 80;
        $_SERVER['REQUEST_URI'] = '/';

        $request = Request::fromEnvironment($_SERVER, fopen($root->url() . '/input', 'r'));

        $this->assertNull($request->getBody());
    }

    /**
     * Factory.
     *
     * Test that factory will return an instance of RequestInterface.
     *
     * @covers \ExtendsFramework\Http\Request\Request::factory()
     * @covers \ExtendsFramework\Http\Request\Request::fromEnvironment()
     */
    public function testFactory(): void
    {
        $root = vfsStream::setup('root', null, [
            'input' => '',
        ]);

        $environment['REQUEST_METHOD'] = 'GET';
        $environment['HTTP_HOST'] = 'www.extends.nl';
        $environment['SERVER_PORT'] = 80;
        $environment['REQUEST_URI'] = '/';

        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $request = Request::factory(RequestInterface::class, $serviceLocator, [
            'environment' => $environment,
            'stream' => fopen($root->url() . '/input', 'r'),
        ]);

        $this->assertInstanceOf(RequestInterface::class, $request);
    }

    /**
     * Stream not resource.
     *
     * Test that an exception will be thrown when filename can not be opened for reading.
     *
     * @covers \ExtendsFramework\Http\Request\Request::fromEnvironment()
     */
    public function testStreamNotResource(): void
    {
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('Stream must be of type resource, string given.');

        Request::fromEnvironment([], 'foo');
    }
}
