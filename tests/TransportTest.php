<?php

namespace Attogram\SharedMedia\Api;

use PHPUnit\Framework\TestCase;

/**
 */
class TransportTest extends TestCase
{
    const VERSION = '1.0.1';

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
            class_exists('\Attogram\SharedMedia\Api\Transport'),
            'class \Attogram\SharedMedia\Api\Transport not found'
        );
        $this->assertTrue(
            defined('\Attogram\SharedMedia\Api\Transport::VERSION'),
            'constant \Attogram\SharedMedia\Api\Transport::VERSION not found'
        );
        $this->assertClassHasAttribute('endpoint', \Attogram\SharedMedia\Api\Transport::class);
        $transport = new \Attogram\SharedMedia\Api\Transport();
        $this->assertObjectHasAttribute('endpoint', $transport);
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
