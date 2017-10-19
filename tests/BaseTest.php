<?php

namespace Attogram\SharedMedia\Api;

use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

/**
 */
class BaseTest extends TestCase
{
    const VERSION = '0.9.5';

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
        $base = new \Attogram\SharedMedia\Api\Base(new NullLogger);
        $this->assertTrue(is_object($base), 'instantiation of Base failed');
    }

    /**
     */
    public function testCallPreGets()
    {
        $call = new \Attogram\SharedMedia\Api\Base(new NullLogger);
        $this->assertFalse(
            $call->getWarnings(),
            'getWarnings without valid call, not returning false'
        );
        $this->assertFalse(
            $call->isBatchcomplete(),
            'isBatchcomplete without valid call, not returning false'
        );
        $this->assertFalse(
            $call->getContinue(),
            'getContinue without valid call, not returning false'
        );
        $this->assertFalse(
            $call->getSroffset(),
            'getSroffset without valid call, not returning false'
        );
        $this->assertFalse(
            $call->getTotalhits(),
            'getTotalhits without valid call, not returning false'
        );
    }
}
