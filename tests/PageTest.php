<?php

namespace Attogram\SharedMedia\Api;

use PHPUnit\Framework\TestCase;

/**
 */
class PageTest extends TestCase
{
    const VERSION = '1.0.0';

    /**
     */
    public function testConstruct()
    {
        $this->assertTrue(
            class_exists('\Attogram\SharedMedia\Api\Page'),
            'class \Attogram\SharedMedia\Api\Page not found'
        );
        $this->assertTrue(
            defined('\Attogram\SharedMedia\Api\Page::VERSION'),
            'constant \Attogram\SharedMedia\Api\Page::VERSION not found'
        );
        $page = new \Attogram\SharedMedia\Api\Page();
        $this->assertTrue(is_object($page), 'instantiation of Page failed');
    }
}
