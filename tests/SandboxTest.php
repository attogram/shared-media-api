<?php

use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Attogram\SharedMedia\Api\Sandbox
 */
class SandboxTest extends TestCase
{
    const VERSION = '0.9.1';

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
        //$sandbox = new \Attogram\SharedMedia\Api\Sandbox();
        //$this->assertTrue(is_object($sandbox), 'instantiation of Sandbox failed');
    }
}
