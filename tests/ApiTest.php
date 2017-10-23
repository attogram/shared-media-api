<?php

namespace Attogram\SharedMedia\Api;

use PHPUnit\Framework\TestCase;

/**
 */
class ApiTest extends TestCase
{
    const VERSION = '0.9.7';

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
        $api = new \Attogram\SharedMedia\Api\Api();
        $this->assertObjectHasAttribute('endpoint', $api);
    }

    /**
     */
    public function testSetEndpoint()
    {
        $call = new \Attogram\SharedMedia\Api\Api();
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
    }
}
