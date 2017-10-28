<?php

require('../../vendor/autoload.php');

use Attogram\SharedMedia\Api\Media;

$media = new Media();
$media->setLimit(1);
$result = $media->search('Albert Einstein');
print_r($result);
