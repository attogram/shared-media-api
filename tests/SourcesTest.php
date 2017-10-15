<?php

namespace Attogram\SharedMedia\Api;

use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Attogram\SharedMedia\Api\Sources
 */
class SourcesTest extends TestCase
{
    const VERSION = '0.9.2';

    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertTrue(
            class_exists('\Attogram\SharedMedia\Api\Sources'),
            'class \Attogram\SharedMedia\Api\Sources not found'
        );
        $this->assertTrue(
            defined('\Attogram\SharedMedia\Api\Sources::VERSION'),
            'constant \Attogram\SharedMedia\Api\Sources::VERSION not found'
        );
        $this->assertClassHasAttribute('sources', \Attogram\SharedMedia\Api\Sources::class);
        $sources = new \Attogram\SharedMedia\Api\Sources();
        $this->assertTrue(is_object($sources), 'instantiation of Sources failed');
        $this->assertObjectHasAttribute('sources', $sources);
    }
}
