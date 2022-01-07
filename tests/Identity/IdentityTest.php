<?php
declare(strict_types=1);

namespace ExtendsFramework\Identity;

use PHPUnit\Framework\TestCase;

class IdentityTest extends TestCase
{
    /**
     * Get identifier.
     *
     * Test that correct identifier is will be returned.
     *
     * @covers \ExtendsFramework\Identity\Identity::__construct()
     * @covers \ExtendsFramework\Identity\Identity::getIdentifier()
     */
    public function testGetIdentifier(): void
    {
        $this->assertSame('foo-bar', (new Identity('foo-bar'))->getIdentifier());
        $this->assertSame(1, (new Identity(1))->getIdentifier());
    }
}
