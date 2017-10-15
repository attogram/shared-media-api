<?php

namespace Attogram\SharedMedia\Api;

use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Attogram\SharedMedia\Api\Sandbox
 */
class SandboxTest extends TestCase
{
    const VERSION = '0.9.2';

    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertTrue(
            class_exists('\Attogram\SharedMedia\Api\Sandbox'),
            'class \Attogram\SharedMedia\Api\Sandbox not found'
        );
        $this->assertTrue(
            defined('\Attogram\SharedMedia\Api\Sandbox::VERSION'),
            'constant \Attogram\SharedMedia\Api\Sandbox::VERSION not found'
        );
    }
}
