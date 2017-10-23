<?php

require('../../vendor/autoload.php');

use Attogram\SharedMedia\Api\File;

$file = new File();
$file->setLimit(1);
$result = $file->search('Albert Einstein');
print_r($result);
