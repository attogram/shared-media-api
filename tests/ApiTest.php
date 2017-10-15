<?php

use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

/**
 * @coversDefaultClass \Attogram\SharedMedia\Api
 */
class ApiTest extends TestCase
{
    const VERSION = '0.9.1';

    public function setUp()
    {
        $this->defaultEndpoint = 'https://commons.wikimedia.org/w/api.php';
        $this->testingUrl = 'https://example.com/api';
    }

    /**
     * @covers ::__construct
     * @covers ::getEndpoint
     */
    public function testConstruct()
    {
        $this->assertTrue(
            class_exists('\Attogram\SharedMedia\Api\Api'),
            'class \Attogram\SharedMedia\Api\Api not found'
        );
        $this->assertTrue(
            defined('\Attogram\SharedMedia\Api\Api::VERSION'),
            'constant \Attogram\SharedMedia\Api\Api::VERSION not found'
        );
        $this->assertClassHasAttribute('endpoint', \Attogram\SharedMedia\Api\Api::class);
        $api = new \Attogram\SharedMedia\Api\Api(new NullLogger);
        $this->assertObjectHasAttribute('endpoint', $api);
    }

    /**
     * @covers ::getEndpoint
     * @covers ::setEndpoint
     */
    public function testSetEndpoint()
    {
        $call = new \Attogram\SharedMedia\Api\Api(new NullLogger);
        $call->setEndpoint($this->testingUrl);
        $this->assertEquals(
            $call->getEndpoint(),
            $this->testingUrl,
            'setEndpoint()/getEndpoint() failed'
        );
    }

    /**
     * @covers ::getClient
     */
    public function testClient()
    {
        $this->assertTrue(
            class_exists('\GuzzleHttp\Client'),
            'class \GuzzleHttp\Client not found'
        );
        $this->assertTrue(
            class_exists('\GuzzleHttp\Exception\ConnectException'),
            'class \GuzzleHttp\Exception\ConnectException not found'
        );
        $call = new \Attogram\SharedMedia\Api\Api(new NullLogger);
        $client = $call->getClient();
        $this->assertInstanceof(
            '\GuzzleHttp\Client',
            $client,
            'guzzle client is not instance of \GuzzleHttp\Client'
        );
        $this->assertTrue(
            method_exists($client, 'request'),
            'guzzle client has no request() method'
        );
        $client2 = $call->getClient();
        $this->assertInstanceof(
            '\GuzzleHttp\Client',
            $client2,
            'second client is not instance of \GuzzleHttp\Client'
        );
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
        $call = new \Attogram\SharedMedia\Api\Api(new NullLogger);
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
