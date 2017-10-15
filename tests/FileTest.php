<?php

use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

/**
 * @coversDefaultClass \Attogram\SharedMedia\Api\File
 */
class FileTest extends TestCase
{
    const VERSION = '0.9.1';

    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $this->assertTrue(
            class_exists('\Attogram\SharedMedia\Api\File'),
            'class \Attogram\SharedMedia\Api\File not found'
        );
        $this->assertTrue(
            defined('\Attogram\SharedMedia\Api\File::VERSION'),
            'constant \Attogram\SharedMedia\Api\File::VERSION not found'
        );
        $file = new \Attogram\SharedMedia\Api\File(new NullLogger);
        $this->assertTrue(is_object($file), 'instantiation of File failed');
    }
}
