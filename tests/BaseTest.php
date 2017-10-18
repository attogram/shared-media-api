<?php

namespace Attogram\SharedMedia\Api;

use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

/**
 * @coversDefaultClass \Attogram\SharedMedia\Base
 */
class BaseTest extends TestCase
{
    const VERSION = '0.9.2';

    /**
     * @covers ::__construct
     * @covers ::getEndpoint
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
        $this->assertClassHasAttribute('endpoint', \Attogram\SharedMedia\Api\Base::class);
        $base = new \Attogram\SharedMedia\Api\Base(new NullLogger);
    }

    /**
     * @covers ::getWarnings
     * @covers ::isBatchcomplete
     * @covers ::getContinue
     * @covers ::getSroffset
     * @covers ::getTotalhits
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
