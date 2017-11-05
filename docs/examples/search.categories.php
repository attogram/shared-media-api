<?php

require('../../vendor/autoload.php');

use Attogram\SharedMedia\Api\Category;

$category = new Category();
$category->setResponseLimit(5);
$results = $category->search('Albert Einstein');
foreach ($results as $result) {
    print_r($result);
}
