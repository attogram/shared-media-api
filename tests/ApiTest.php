<?php

namespace Attogram\SharedMedia\Api;

use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

/**
 */
class ApiTest extends TestCase
{
    const VERSION = '0.9.5';

    public $defaultEndpoint;
    public $testingUrl;

    public function setUp()
    {
        $this->defaultEndpoint = 'https://commons.wikimedia.org/w/api.php';
        $this->testingUrl = 'https://example.com/api';
    }

    /**
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
}
