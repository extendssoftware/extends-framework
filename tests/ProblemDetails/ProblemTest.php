<?php
declare(strict_types=1);

namespace ExtendsFramework\ProblemDetails;

use PHPUnit\Framework\TestCase;

class ProblemTest extends TestCase
{
    /**
     * Getters.
     *
     * Test that getters will return correct values.
     *
     * @covers \ExtendsFramework\ProblemDetails\ProblemDetails::__construct()
     * @covers \ExtendsFramework\ProblemDetails\ProblemDetails::getType()
     * @covers \ExtendsFramework\ProblemDetails\ProblemDetails::getTitle()
     * @covers \ExtendsFramework\ProblemDetails\ProblemDetails::getDetail()
     * @covers \ExtendsFramework\ProblemDetails\ProblemDetails::getStatus()
     * @covers \ExtendsFramework\ProblemDetails\ProblemDetails::getInstance()
     * @covers \ExtendsFramework\ProblemDetails\ProblemDetails::getAdditional()
     */
    public function testGetters(): void
    {
        $problem = new ProblemDetails(
            '/foo/type',
            'Problem title',
            'Problem detail',
            400,
            '/foo/instance',
            [
                'foo' => 'bar',
            ]
        );

        $this->assertSame('/foo/type', $problem->getType());
        $this->assertSame('Problem title', $problem->getTitle());
        $this->assertSame('Problem detail', $problem->getDetail());
        $this->assertSame(400, $problem->getStatus());
        $this->assertSame('/foo/instance', $problem->getInstance());
        $this->assertSame(['foo' => 'bar'], $problem->getAdditional());
    }
}
