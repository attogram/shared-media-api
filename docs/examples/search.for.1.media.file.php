<?php

require('../../vendor/autoload.php');

use Attogram\SharedMedia\Api\Media;

$media = new Media();
$media->setResponseLimit(1);
$result = $media->search('Albert Einstein');
print_r($result);
