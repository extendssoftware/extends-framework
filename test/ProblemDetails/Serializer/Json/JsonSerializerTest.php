<?php
declare(strict_types=1);

namespace ExtendsFramework\ProblemDetails\Serializer\Json;

use ExtendsFramework\ProblemDetails\ProblemDetailsInterface;
use PHPUnit\Framework\TestCase;

class JsonSerializerTest extends TestCase
{
    /**
     * Serialize.
     *
     * Test that serialize will return correct JSON string.
     *
     * @covers \ExtendsFramework\ProblemDetails\Serializer\Json\JsonSerializer::serialize()
     */
    public function testSerialize(): void
    {
        $problem = $this->createMock(ProblemDetailsInterface::class);
        $problem
            ->expects($this->once())
            ->method('getType')
            ->willReturn('/foo/type');

        $problem
            ->expects($this->once())
            ->method('getTitle')
            ->willReturn('Problem title');

        $problem
            ->expects($this->once())
            ->method('getDetail')
            ->willReturn('Problem detail');

        $problem
            ->expects($this->once())
            ->method('getStatus')
            ->willReturn(400);

        $problem
            ->expects($this->once())
            ->method('getInstance')
            ->willReturn('/foo/instance');

        $problem
            ->expects($this->once())
            ->method('getAdditional')
            ->willReturn([
                'foo' => 'bar',
            ]);

        /**
         * @var ProblemDetailsInterface $problem
         */
        $serializer = new JsonSerializer();

        $this->assertSame(
            json_encode([
                'type' => '/foo/type',
                'title' => 'Problem title',
                'detail' => 'Problem detail',
                'status' => 400,
                'instance' => '/foo/instance',
                'foo' => 'bar',
            ], JSON_UNESCAPED_SLASHES),
            $serializer->serialize($problem)
        );
    }
}
