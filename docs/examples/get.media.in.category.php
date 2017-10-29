<?php

require('../../vendor/autoload.php');

use Attogram\SharedMedia\Api\Category;
use Attogram\SharedMedia\Api\Media;

// Get the first result from a search of the category namespace
$category = new Category();
$category->setLimit(1);
$myCategory = $category->search('Albert Einstein');
if (empty($myCategory)) {
    print 'Category Not Found';
    return;
}
print_r($myCategory);

// Get the first 2 Media files in a category
$media = new Media();
$media->setLimit(2);
$media->pageid = $myCategory[0]['pageid'];
$myMedias = $media->getMediaInCategory();
if (empty($myMedias)) {
    print 'Media Not Found';
    return;
}
foreach ($myMedias as $myMedia) {
    print_r($myMedia);
}
