<?php

namespace Attogram\SharedMedia\Api;

use PHPUnit\Framework\TestCase;

/**
 */
class CategoryTest extends TestCase
{
    const VERSION = '1.0.0';

    /**
     */
    public function testConstruct()
    {
        $this->assertTrue(
            class_exists('\Attogram\SharedMedia\Api\Category'),
            'class \Attogram\SharedMedia\Api\Category not found'
        );
        $this->assertTrue(
            defined('\Attogram\SharedMedia\Api\Category::VERSION'),
            'constant \Attogram\SharedMedia\Api\Category::VERSION not found'
        );
        $category = new \Attogram\SharedMedia\Api\Category();
        $this->assertTrue(is_object($category), 'instantiation of Category failed');
    }
}
