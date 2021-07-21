<?php
declare(strict_types=1);

namespace ExtendsFramework\Application\Http\Renderer;

use ExtendsFramework\Http\Response\ResponseInterface;
use PHPUnit\Framework\TestCase;

class RendererTest extends TestCase
{
    /**
     * Render.
     *
     * Test that response will be rendered: headers sent, body encoded and HTTP status code set.
     *
     * @covers \ExtendsFramework\Application\Http\Renderer\Renderer::render()
     * @runInSeparateProcess
     */
    public function testRender(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn('{"foo":"bar"}');

        $response
            ->expects($this->once())
            ->method('getHeaders')
            ->willReturn([
                'Accept' => [
                    'text/html',
                    'application/xhtml+xml',
                    'application/xml;q=0.9',
                    '*/*;q=0.8',
                ],
                'Content-Type' => 'application/json',
                'Content-Length' => '13',
            ]);

        $response
            ->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200);

        /**
         * @var ResponseInterface $response
         */
        $renderer = new Renderer();

        ob_start();
        $renderer->render($response);
        $output = ob_get_clean();

        $this->assertSame('{"foo":"bar"}', $output);
        $this->assertSame([
            'Accept: text/html, application/xhtml+xml, application/xml;q=0.9, */*;q=0.8',
            'Content-Type: application/json',
            'Content-Length: 13',
        ], xdebug_get_headers());
        $this->assertSame(200, http_response_code());
    }

    /**
     * Render.
     *
     * Test that no body will be send.
     *
     * @covers \ExtendsFramework\Application\Http\Renderer\Renderer::render()
     * @runInSeparateProcess
     */
    public function testEmptyBody(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn(null);

        $response
            ->expects($this->once())
            ->method('getHeaders')
            ->willReturn([
                'Accept-Charset' => 'utf-8',
                'Content-Type' => 'application/json',
                'Content-Length' => '0',
            ]);

        $response
            ->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200);

        /**
         * @var ResponseInterface $response
         */
        $renderer = new Renderer();

        ob_start();
        $renderer->render($response);
        $output = ob_get_clean();

        $this->assertSame('', $output);
        $this->assertSame([
            'Accept-Charset: utf-8',
            'Content-Type: application/json',
            'Content-Length: 0',
        ], xdebug_get_headers());
        $this->assertSame(200, http_response_code());
    }
}
