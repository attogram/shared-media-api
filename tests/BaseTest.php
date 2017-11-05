<?php

namespace Attogram\SharedMedia\Api;

use PHPUnit\Framework\TestCase;

/**
 */
class BaseTest extends TestCase
{
    const VERSION = '1.0.0';

    /**
     */
    public function testConstruct()
    {
        $this->assertTrue(
            class_exists('\Attogram\SharedMedia\Api\Base'),
            'class \Attogram\SharedMedia\Api\Base not found'
        );
        $this->assertTrue(
            defined('\Attogram\SharedMedia\Api\Base::VERSION'),
            'constant \Attogram\SharedMedia\Api\Base::VERSION not found'
        );
        $base = new \Attogram\SharedMedia\Api\Base();
        $this->assertTrue(is_object($base), 'instantiation of Base failed');
    }
}
