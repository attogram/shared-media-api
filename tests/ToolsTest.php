<?php

namespace Attogram\SharedMedia\Api;

use PHPUnit\Framework\TestCase;

/**
 */
class ToolsTest extends TestCase
{
    const VERSION = '0.10.0';

    /**
     */
    public function testConstruct()
    {
        $this->assertTrue(
            class_exists('\Attogram\SharedMedia\Api\Tools'),
            'class \Attogram\SharedMedia\Api\Tools not found'
        );
        $this->assertTrue(
            defined('\Attogram\SharedMedia\Api\Tools::VERSION'),
            'constant \Attogram\SharedMedia\Api\Tools::VERSION not found'
        );
        $tools = new \Attogram\SharedMedia\Api\Tools();
        $this->assertTrue(is_object($tools), 'instantiation of Tools failed');
    }
}
