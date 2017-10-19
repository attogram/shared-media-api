<?php

namespace Attogram\SharedMedia\Api;

use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

/**
 *
 */
class FileTest extends TestCase
{
    const VERSION = '0.9.4';

    /**
     * @covers File
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
