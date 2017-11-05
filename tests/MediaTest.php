<?php

namespace Attogram\SharedMedia\Api;

use PHPUnit\Framework\TestCase;

/**
 */
class MediaTest extends TestCase
{
    const VERSION = '1.0.0';

    /**
     */
    public function testConstruct()
    {
        $this->assertTrue(
            class_exists('\Attogram\SharedMedia\Api\Media'),
            'class \Attogram\SharedMedia\Api\Media not found'
        );
        $this->assertTrue(
            defined('\Attogram\SharedMedia\Api\Media::VERSION'),
            'constant \Attogram\SharedMedia\Api\Media::VERSION not found'
        );
        $media = new \Attogram\SharedMedia\Api\Media();
        $this->assertTrue(is_object($media), 'instantiation of Media failed');
    }
}
